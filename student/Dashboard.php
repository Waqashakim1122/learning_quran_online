```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        log_message('debug', 'Session in Dashboard constructor: ' . print_r($this->session->userdata(), TRUE));
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please log in to access the dashboard.');
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'student') {
            show_error('You are not authorized to access this page', 403);
        }
        $this->load->model('User_model');
        $this->load->model('Course_model');
        $this->load->model('Enrollment_model');
        $this->load->model('Instructor_model');
        $this->load->model('Schedule_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data = [
            'title' => 'Student Dashboard',
            'user' => $this->User_model->get_user($user_id),
            'enrolled_courses' => $this->Enrollment_model->get_student_courses($user_id),
            'enrolled_course_ids' => array_column($this->Enrollment_model->get_student_courses($user_id), 'course_id'),
            'all_courses' => $this->Course_model->get_available_courses()
        ];
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/dashboard/index', $data);
        $this->load->view('student/templates/footer', $data);
    }

    public function courses() {
        $user_id = $this->session->userdata('user_id');
        $level = $this->input->get('level');
        
        $data = [
            'title' => 'Available Courses',
            'courses' => $this->Course_model->get_available_courses($level),
            'enrolled_course_ids' => array_column($this->Enrollment_model->get_student_courses($user_id), 'course_id')
        ];
        
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/dashboard/courses', $data);
        $this->load->view('student/templates/footer', $data);
    }

    public function my_courses() {
        $user_id = $this->session->userdata('user_id');
        log_message('debug', 'Current user_id from session (my_courses): ' . $user_id);
        $data = [
            'title' => 'My Enrolled Courses',
            'enrolled_courses' => $this->Enrollment_model->get_student_courses($user_id),
        ];
        
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/dashboard/my_courses', $data);
        $this->load->view('student/templates/footer', $data);
    }

   public function course_detail($course_id) {
    if (!$course_id || !is_numeric($course_id)) {
        $this->session->set_flashdata('error', 'Invalid course ID.');
        redirect('student/dashboard');
    }

    $course = $this->Course_model->get_course_with_details($course_id);
    if (!$course) {
        $this->session->set_flashdata('error', 'Course not found.');
        redirect('student/dashboard');
    }

    // Set default values for missing properties to prevent PHP errors
    $course->certificate_available = $course->certificate_available ?? 0;
    $course->short_description = $course->short_description ?? '';
    $course->learning_outcomes = $course->learning_outcomes ?? '';
   
    $course->language = $course->language ?? 'english';
    
    // Handle instructor data - create empty object if not present
    if (!isset($course->instructor) || !$course->instructor) {
        $course->instructor = (object)[
            'profile_image' => null,
            'name' => 'TBD',
            'specialization' => 'Not assigned',
            'bio' => 'Instructor will be assigned soon.'
        ];
    }

    $user_id = $this->session->userdata('user_id');
    $data = [
        'title' => htmlspecialchars($course->course_name) . ' - Course Details',
        'course' => $course,
        'enrolled_course_ids' => array_column($this->Enrollment_model->get_student_courses($user_id), 'course_id'),
        'user' => $this->User_model->get_user($user_id),
    ];
    
   
    $this->load->view('student/courses/course_detail', $data);
    $this->load->view('student/templates/footer', $data);
}

    public function enroll($course_id = null) {
        $course_id = $course_id ?: $this->input->post('course_id');

        if (!$course_id || !is_numeric($course_id)) {
            $this->session->set_flashdata('error', 'Invalid course ID.');
            redirect('student/dashboard/courses');
        }

        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('student/dashboard/courses');
        }

        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            $this->session->set_flashdata('error', 'Session error: User not found. Please log in again.');
            redirect('student/dashboard');
        }

        // Check if already enrolled
        if ($this->Enrollment_model->is_enrolled($user_id, $course_id)) {
            $this->session->set_flashdata('error', 'You are already enrolled in this course.');
            redirect('student/dashboard/courses');
        }

        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('age', 'Age', 'required|integer|greater_than_equal_to[5]|less_than_equal_to[99]');
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[male,female,other]');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('current_level', 'Current Level', 'required');
        $this->form_validation->set_rules('preferred_schedule', 'Preferred Schedule', 'required');
        $this->form_validation->set_rules('preferred_days[]', 'Preferred Days', 'required');
        $this->form_validation->set_rules('class_duration', 'Class Duration', 'required');
        $this->form_validation->set_rules('learning_goal', 'Learning Goals', 'required');
        $this->form_validation->set_rules('terms_agree', 'Terms Agreement', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['course'] = $course;
            $data['instructors'] = $this->Instructor_model->get_instructors();
            $data['title'] = 'Enroll in Quran Course';
            $this->load->view('student/templates/header', $data);
            $this->load->view('student/templates/sidebar', $data);
            $this->load->view('student/courses/enroll', $data);
            $this->load->view('student/templates/footer', $data);
        } else {
            $preferred_days = $this->input->post('preferred_days');
            if (!is_array($preferred_days)) {
                $preferred_days = $preferred_days ? [$preferred_days] : [];
            }

            // Validate preferred days
            if (empty($preferred_days)) {
                $this->session->set_flashdata('error', 'Please select at least one preferred day.');
                redirect('student/dashboard/enroll/' . $course_id);
            }

            $enrollment_data = [
                'student_id' => $user_id,
                'course_id' => $course_id,
                'preferred_instructor_id' => $this->input->post('preferred_instructor_id') ?: NULL,
                'student_preferred_instructor_id' => NULL,
                'assigned_instructor_id' => NULL,
                'instructor_id' => NULL,
                'enrollment_date' => date('Y-m-d'),
                'enrolled_at' => date('Y-m-d H:i:s'),
                'status' => 'pending_approval',
                'progress' => 0,
                'learning_goal' => $this->input->post('learning_goal'),
                'current_level' => $this->input->post('current_level'),
                'preferred_schedule' => $this->input->post('preferred_schedule'),
                'preferred_days' => implode(',', $preferred_days),
                'class_duration' => $this->input->post('class_duration'),
                'max_hours_per_day' => $this->input->post('class_duration') ? ceil($this->input->post('class_duration') / 60) : 1,
                'notes' => $this->input->post('notes'),
                'admin_assign_instructor' => $this->input->post('admin_assign_instructor') ? 1 : 0,
                'preferred_style' => $this->input->post('preferred_style') ?: NULL,
                'preferred_language' => $this->input->post('preferred_language') ?: 'english',
                'last_accessed' => date('Y-m-d H:i:s')
            ];

            if ($this->Enrollment_model->create_enrollment($enrollment_data)) {
                $this->session->set_flashdata('success', 'Enrollment request submitted successfully. Awaiting admin approval.');
                redirect('student/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Enrollment failed. Please try again.');
                redirect('student/dashboard/enroll/' . $course_id);
            }
        }
    }

    public function validate_preferred_days($days) {
        if (!is_array($days) || empty($days)) {
            $this->form_validation->set_message('validate_preferred_days', 'Please select at least one preferred day.');
            return FALSE;
        }

        $valid_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $day) {
            if (!in_array($day, $valid_days)) {
                $this->form_validation->set_message('validate_preferred_days', 'Invalid day selected.');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function course($enrollment_id) {
        $user_id = $this->session->userdata('user_id');
        $enrollment = $this->Enrollment_model->get_enrollment_details($enrollment_id);

        if (!$enrollment || $enrollment->student_id != $user_id || !in_array($enrollment->status, ['active', 'completed'])) {
            $this->session->set_flashdata('error', 'You do not have access to this course.');
            redirect('student/dashboard');
        }

        $data = [
            'title' => $enrollment->course_name,
            'enrollment' => $enrollment
        ];
        
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/course_details', $data);
        $this->load->view('student/templates/footer', $data);
    }

    public function calendar() {
        $user_id = $this->session->userdata('user_id');
        $month = date('m');
        $year = date('Y');
        if ($this->input->get('month') && $this->input->get('year')) {
            $month = $this->input->get('month');
            $year = $this->input->get('year');
        }

        $data = [
            'title' => 'Student Calendar',
            'user' => $this->User_model->get_user($user_id),
            'schedules' => $this->Schedule_model->get_student_schedules($user_id, $month, $year),
            'stats' => $this->Schedule_model->get_schedule_stats($user_id, $month, $year),
            'current_month' => $month,
            'current_year' => $year,
            'days' => ['SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI']
        ];
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/dashboard/calendar', $data);
        $this->load->view('student/templates/footer', $data);
    }

    public function delete_schedule($schedule_id) {
        $user_id = $this->session->userdata('user_id');
        if ($this->Schedule_model->delete_schedule($schedule_id, $user_id)) {
            $this->session->set_flashdata('success', 'Schedule deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete schedule.');
        }
        redirect('student/dashboard/calendar');
    }
}
?>