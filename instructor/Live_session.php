<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_session extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Live_session_model', 'Enrollment_model', 'Instructor_model']);
        $this->load->library('form_validation');
        $this->load->helper('url');
        date_default_timezone_set('Asia/Karachi');

        // Check if user is logged in and is an instructor
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'instructor') {
            redirect('login');
        }

        // Check instructor profile status
        $this->check_profile_status();
    }

    /**
     * Check if instructor profile is approved
     */
    private function check_profile_status() {
        $profile = $this->Instructor_model->get_profile($this->session->userdata('user_id'));
        if (!$profile || $profile->status !== 'approved') {
            redirect('instructor/dashboard/pending');
        }
    }

    /**
     * Display sessions (instructor: all, student: their own)
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $data['title'] = 'Live Sessions';
        $data['active_tab'] = 'live_session';
        if ($role === 'instructor') {
            $data['sessions'] = $this->Live_session_model->get_instructor_sessions($user_id);
        } elseif ($role === 'student') {
            $data['sessions'] = $this->Live_session_model->get_student_sessions($user_id);
        } else {
            show_error('Access denied', 403);
        }
          $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/templates/sidebar', $data);
        $this->load->view('instructor/live_session/index', $data);
    }

    /**
     * Schedule a new one-to-one session (instructor only)
     */
    public function schedule() {
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Schedule Session';
        $data['active_tab'] = 'live_session';

        // Get instructor's courses
        $data['courses'] = $this->get_instructor_courses($user_id);
        if (empty($data['courses'])) {
            $data['error'] = 'No courses assigned to you. Please contact the administrator.';
            $this->load->view('instructor/live_session/schedule', $data);
            return;
        }

        $this->form_validation->set_rules('course_id', 'Course', 'required|integer');
        $this->form_validation->set_rules('student_id', 'Student', 'required|integer');
        $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[1000]');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required|callback_valid_datetime');
        $this->form_validation->set_rules('duration', 'Duration (minutes)', 'required|integer|greater_than[0]|less_than[121]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('instructor/live_session/schedule', $data);
        } else {
            $start_time = $this->input->post('start_time');
            $duration = (int)$this->input->post('duration');
            $end_time = date('Y-m-d H:i:s', strtotime($start_time . " + $duration minutes"));

            // Validate student enrollment
            $student_id = $this->input->post('student_id');
            $course_id = $this->input->post('course_id');
            
            if (!$this->is_student_enrolled($student_id, $course_id, $user_id)) {
                $data['error'] = 'Selected student is not enrolled in this course with you.';
                log_message('error', "Student ID $student_id not enrolled in course ID $course_id for instructor ID $user_id");
                $this->load->view('instructor/live_session/schedule', $data);
                return;
            }

            $session_data = [
                'instructor_id' => $user_id,
                'course_id' => $course_id,
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'max_students' => 1,
                'meeting_link' => 'https://meet.jit.si/quran-learning-session-' . uniqid()
            ];

            $session_id = $this->Live_session_model->create_session($session_data, $student_id);
            if ($session_id) {
                $this->session->set_flashdata('success', 'Session scheduled successfully.');
                redirect('instructor/live_session');
            } else {
                $data['error'] = 'Failed to schedule session. Please try again.';

                 $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/templates/sidebar', $data);
                $this->load->view('instructor/live_session/schedule', $data);
            }
        }
    }

    /**
     * Get instructor's courses
     */
    private function get_instructor_courses($instructor_id) {
        $this->db->distinct();
        $this->db->select('c.course_id, c.course_name');
        $this->db->from('courses c');
        $this->db->join('enrollments e', 'e.course_id = c.course_id');
        $this->db->where('e.assigned_instructor_id', $instructor_id);
        $this->db->where_in('e.status', ['active', 'approved']);
        $this->db->where('c.is_active', 1);
        $query = $this->db->get();
        
        if ($query === FALSE) {
            log_message('error', 'DB Error in get_instructor_courses: ' . print_r($this->db->error(), true));
            return [];
        }
        
        return $query->result();
    }

    /**
     * Check if student is enrolled in course with this instructor
     */
    private function is_student_enrolled($student_id, $course_id, $instructor_id) {
        $this->db->select('enrollment_id');
        $this->db->where('student_id', $student_id);
        $this->db->where('course_id', $course_id);
        $this->db->where('assigned_instructor_id', $instructor_id);
        $this->db->where_in('status', ['active', 'approved']);
        $query = $this->db->get('enrollments');
        
        if ($query === FALSE) {
            log_message('error', 'DB Error in is_student_enrolled: ' . print_r($this->db->error(), true));
            return false;
        }
        
        return $query->num_rows() > 0;
    }

    /**
     * Get enrolled students for a course (AJAX)
     */
    public function get_enrolled_students($course_id) {
        if ($this->session->userdata('role') !== 'instructor') {
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        $course_id = (int)$course_id; // Sanitize input
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('u.id, u.name, e.enrollment_id');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->where('e.course_id', $course_id);
        $this->db->where('e.assigned_instructor_id', $user_id);
        $this->db->where('u.role', 'student');
        $this->db->where_in('e.status', ['active', 'approved']);
        $this->db->order_by('u.name', 'ASC'); // Sort students by name
        $query = $this->db->get();
        
        if ($query === FALSE) {
            log_message('error', 'DB Error in get_enrolled_students for course_id ' . $course_id . ': ' . print_r($this->db->error(), true));
            echo json_encode(['error' => 'Database error occurred']);
            return;
        }
        
        $students = $query->result();
        if (empty($students)) {
            log_message('debug', 'No students found for course_id ' . $course_id . ' and instructor_id ' . $user_id);
        }
        
        echo json_encode($students);
    }

    /**
     * Join a session (student only)
     */
    public function join($session_id) {
        if ($this->session->userdata('role') !== 'student') {
            show_error('Access denied', 403);
        }

        $user_id = $this->session->userdata('user_id');
        $session = $this->Live_session_model->get_session($session_id);

        if (!$session || $session->student_id !== $user_id) {
            show_error('Session not found or access denied', 404);
        }

        // Mark attendance
        $this->Live_session_model->mark_attendance($session_id, $user_id);

        // Redirect to Jitsi meeting
        redirect($session->meeting_link);
    }

    /**
     * Custom validation for datetime
     */
    public function valid_datetime($str) {
        if (!strtotime($str)) {
            $this->form_validation->set_message('valid_datetime', 'The {field} must be a valid date and time.');
            return FALSE;
        }
        if (strtotime($str) < time()) {
            $this->form_validation->set_message('valid_datetime', 'The {field} must be in the future.');
            return FALSE;
        }
        return TRUE;
    }
}