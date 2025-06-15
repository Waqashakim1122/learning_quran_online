<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Schedule_model', 'Instructor_model', 'Student_model', 'Course_model', 'Messages_model']);
        $this->load->library(['session', 'form_validation']);
        
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'instructor') {
            $this->session->set_flashdata('error', 'Please login as instructor');
            redirect('auth/login');
        }
        
        $this->check_profile_status();
    }
    
    private function check_profile_status() {
        $profile = $this->Instructor_model->get_profile($this->session->userdata('user_id'));
        if (!$profile || $profile->status !== 'approved') {
            $this->session->set_flashdata('error', 'Profile not approved');
            redirect('instructor/dashboard/pending');
        }
    }

    public function index() {
        $instructor_id = $this->session->userdata('user_id');
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'My Schedule',
            'active_tab' => 'schedule',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
         $this->load->view('instructor/templates/sidebar', $data);
        $this->load->view('instructor/schedule/index', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function get_events() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $instructor_id = $this->session->userdata('user_id');
        $start_date = $this->input->get('start');
        $end_date = $this->input->get('end');
        
        $events = $this->Schedule_model->get_calendar_events($instructor_id, $start_date, $end_date);
        $calendar_events = [];
        
        foreach ($events as $event) {
            $start = date('Y-m-d\TH:i:s', strtotime($event->start_time));
            $end = date('Y-m-d\TH:i:s', strtotime($event->end_time));
            
            // Color coding based on attendance status or scheduled
            $color = '#3b82f6'; // Blue for scheduled
            $status_display = 'Scheduled';
            if ($event->attendance_status == 'present') {
                $color = '#22c55e'; // Green for present
                $status_display = 'Present';
            } elseif ($event->attendance_status == 'absent') {
                $color = '#ef4444'; // Red for absent
                $status_display = 'Absent';
            }
            
            $calendar_events[] = [
                'id' => $event->id,
                'title' => ($event->course_name ?? 'Class') . ' - ' . ($event->student_name ?? 'Student') . ' (' . $status_display . ')',
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'extendedProps' => [
                    'status' => $event->status,
                    'attendance_status' => $event->attendance_status ?? 'scheduled',
                    'enrollment_id' => $event->enrollment_id,
                    'course_id' => $event->course_id,
                    'student_id' => $event->student_id
                ]
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($calendar_events);
    }

    public function create($enrollment_id = null) {
        $instructor_id = $this->session->userdata('user_id');
        
        if (!$enrollment_id || !is_numeric($enrollment_id)) {
            log_message('error', 'Attempted to access create without enrollment_id by user_id: ' . $instructor_id);
            $this->session->set_flashdata('error', 'Please select a student to schedule a class');
            redirect('instructor/students');
        }
        
        if (!$this->Instructor_model->is_student_assigned($enrollment_id, $instructor_id)) {
            $this->session->set_flashdata('error', 'You are not assigned to this student');
            redirect('instructor/students');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('course_id', 'Course', 'required|numeric');
            $this->form_validation->set_rules('start_time', 'Start Time', 'required');
            $this->form_validation->set_rules('end_time', 'End Time', 'required');
            
            if ($this->form_validation->run()) {
                $start_time = $this->input->post('start_time');
                $end_time = $this->input->post('end_time');
                
                if (strtotime($end_time) <= strtotime($start_time)) {
                    $this->session->set_flashdata('error', 'End time must be after start time');
                } elseif (!$this->Schedule_model->is_time_slot_available($instructor_id, $start_time, $end_time)) {
                    $this->session->set_flashdata('error', 'Time slot conflicts with another class or is outside your availability');
                } else {
                    $data = [
                        'enrollment_id' => $enrollment_id,
                        'course_id' => $this->input->post('course_id'),
                        'instructor_id' => $instructor_id,
                        'student_id' => $this->Student_model->get_student_by_enrollment($enrollment_id)->user_id,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'status' => 'scheduled'
                    ];
                    
                    $class_id = $this->Schedule_model->create_class($data);
                    if ($class_id) {
                        $conversation = $this->Messages_model->get_conversation_by_enrollment($enrollment_id);
                        if ($conversation) {
                            $message_data = [
                                'conversation_id' => $conversation->conversation_id,
                                'sender_id' => $instructor_id,
                                'recipient_id' => $conversation->student_id,
                                'message_text' => "New class scheduled from " . date('M j, Y g:i A', strtotime($start_time)) . " to " . date('g:i A', strtotime($end_time)) . "."
                            ];
                            $this->Messages_model->send_message($message_data);
                            $this->Messages_model->update_conversation_timestamp($conversation->conversation_id);
                        }
                        $this->session->set_flashdata('success', 'Class scheduled successfully');
                        redirect('instructor/schedule');
                    } else {
                        $this->session->set_flashdata('error', 'Failed to schedule class');
                    }
                }
            }
        }
        
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'Schedule New Class',
            'courses' => $this->Instructor_model->get_instructor_courses($instructor_id),
            'availability' => $this->Instructor_model->get_instructor_availability($instructor_id),
            'student' => $this->Student_model->get_student_by_enrollment($enrollment_id),
            'active_tab' => 'schedule',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/schedule/create', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function edit($class_id = null) {
        $instructor_id = $this->session->userdata('user_id');
        
        if (!$class_id || !is_numeric($class_id)) {
            $this->session->set_flashdata('error', 'Please select a class to edit');
            redirect('instructor/schedule');
        }
        
        $class = $this->Schedule_model->get_class($class_id);
        
        if (!$class || $class->instructor_id != $instructor_id) {
            $this->session->set_flashdata('error', 'Invalid class or unauthorized access');
            redirect('instructor/schedule');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('course_id', 'Course', 'required|numeric');
            $this->form_validation->set_rules('start_time', 'Start Time', 'required');
            $this->form_validation->set_rules('end_time', 'End Time', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[scheduled,completed,cancelled]');
            
            if ($this->form_validation->run()) {
                $start_time = $this->input->post('start_time');
                $end_time = $this->input->post('end_time');
                
                if (strtotime($end_time) <= strtotime($start_time)) {
                    $this->session->set_flashdata('error', 'End time must be after start time');
                } elseif (!$this->Schedule_model->is_time_slot_available($instructor_id, $start_time, $end_time, $class_id)) {
                    $this->session->set_flashdata('error', 'Time slot conflicts with another class or is outside your availability');
                } else {
                    $data = [
                        'course_id' => $this->input->post('course_id'),
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'status' => $this->input->post('status')
                    ];
                    
                    if ($this->Schedule_model->update_class($class_id, $data)) {
                        $conversation = $this->Messages_model->get_conversation_by_enrollment($class->enrollment_id);
                        if ($conversation) {
                            $message_data = [
                                'conversation_id' => $conversation->conversation_id,
                                'sender_id' => $instructor_id,
                                'recipient_id' => $conversation->student_id,
                                'message_text' => "Class updated: " . date('M j, Y g:i A', strtotime($start_time)) . " to " . date('g:i A', strtotime($end_time)) . ". Status: " . ucfirst($data['status'])
                            ];
                            $this->Messages_model->send_message($message_data);
                            $this->Messages_model->update_conversation_timestamp($conversation->conversation_id);
                        }
                        $this->session->set_flashdata('success', 'Class updated successfully');
                        redirect('instructor/schedule');
                    } else {
                        $this->session->set_flashdata('error', 'Failed to update class');
                    }
                }
            }
        }
        
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'Edit Class',
            'class' => $class,
            'courses' => $this->Instructor_model->get_instructor_courses($instructor_id),
            'availability' => $this->Instructor_model->get_instructor_availability($instructor_id),
            'student' => $this->Student_model->get_student_by_enrollment($class->enrollment_id),
            'active_tab' => 'schedule',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/schedule/edit', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function complete_class($class_id) {
        $instructor_id = $this->session->userdata('user_id');
        $class = $this->Schedule_model->get_class($class_id);
        
        if (!$class || $class->instructor_id != $instructor_id) {
            $this->session->set_flashdata('error', 'Invalid class or unauthorized access');
            redirect('instructor/schedule');
        }
        
        if ($class->status !== 'scheduled') {
            $this->session->set_flashdata('error', 'Class cannot be completed');
            redirect('instructor/schedule');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('attendance_status', 'Attendance Status', 'required|in_list[present,absent]');
            $this->form_validation->set_rules('notes', 'Notes', 'trim|max_length[500]');
            
            if ($this->form_validation->run()) {
                $class_data = ['status' => 'completed'];
                $attendance_data = [
                    'class_id' => $class_id,
                    'enrollment_id' => $class->enrollment_id,
                    'student_id' => $class->student_id,
                    'instructor_id' => $instructor_id,
                    'attendance_status' => $this->input->post('attendance_status'),
                    'marked_at' => date('Y-m-d H:i:s'),
                    'notes' => $this->input->post('notes')
                ];
                
                if ($this->Schedule_model->update_class($class_id, $class_data) && 
                    $this->Schedule_model->mark_attendance($attendance_data)) {
                    
                    // Notify student
                    $conversation = $this->Messages_model->get_conversation_by_enrollment($class->enrollment_id);
                    if ($conversation) {
                        $message_data = [
                            'conversation_id' => $conversation->conversation_id,
                            'sender_id' => $instructor_id,
                            'recipient_id' => $conversation->student_id,
                            'message_text' => "Class completed. Attendance: " . ucfirst($attendance_data['attendance_status']) . ". " . ($attendance_data['notes'] ? "Notes: " . $attendance_data['notes'] : "")
                        ];
                        $this->Messages_model->send_message($message_data);
                        $this->Messages_model->update_conversation_timestamp($conversation->conversation_id);
                    }
                    
                    // Schedule next class based on enrollment preferences
                    $this->Schedule_model->generate_next_class($class->enrollment_id, $instructor_id);
                    
                    $this->session->set_flashdata('success', 'Class completed and attendance marked successfully');
                    redirect('instructor/schedule');
                } else {
                    $this->session->set_flashdata('error', 'Failed to complete class');
                }
            }
        }
        
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'Complete Class',
            'class' => $class,
            'student' => $this->Student_model->get_student_by_enrollment($class->enrollment_id),
            'active_tab' => 'schedule',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/schedule/complete', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function cancel($class_id) {
        $instructor_id = $this->session->userdata('user_id');
        $class = $this->Schedule_model->get_class($class_id);
        
        if (!$class || $class->instructor_id != $instructor_id) {
            $this->session->set_flashdata('error', 'Invalid class or unauthorized access');
            redirect('instructor/schedule');
        }
        
        if ($this->Schedule_model->update_class($class_id, ['status' => 'cancelled'])) {
            $conversation = $this->Messages_model->get_conversation_by_enrollment($class->enrollment_id);
            if ($conversation) {
                $message_data = [
                    'conversation_id' => $conversation->conversation_id,
                    'sender_id' => $instructor_id,
                    'recipient_id' => $conversation->student_id,
                    'message_text' => "Class cancelled: " . date('M j, Y g:i A', strtotime($class->start_time))
                ];
                $this->Messages_model->send_message($message_data);
                $this->Messages_model->update_conversation_timestamp($conversation->conversation_id);
            }
            $this->session->set_flashdata('success', 'Class cancelled successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel class');
        }
        
        redirect('instructor/schedule');
    }
}