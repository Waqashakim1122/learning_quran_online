<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_session extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Live_session_model');
        $this->load->helper('url');
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
        
        // Check if user is a student
        if ($this->session->userdata('role') !== 'student') {
            show_error('Access denied. Student access required.', 403);
        }
    }
    
    /**
     * Display student's live sessions
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        // Prepare data array
        $data = array();
        $data['title'] = 'Live Sessions';
        $data['active_tab'] = 'live_session';
        $data['sessions'] = $this->Live_session_model->get_student_sessions($user_id);
        
        // Debug: Log session data
        log_message('debug', 'Student sessions for user ' . $user_id . ': ' . json_encode($data['sessions']));
        
        // Load views with data
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/Live_session/index', $data);
        $this->load->view('student/templates/footer', $data);
    }
    
    /**
     * Join a live session
     */
    public function join($session_id) {
        $user_id = $this->session->userdata('user_id');
        
        // Validate session ID
        if (!is_numeric($session_id)) {
            show_error('Invalid session ID', 400);
        }
        
        // Get session details
        $session = $this->Live_session_model->get_session($session_id);
        
        // Check if session exists
        if (!$session) {
            log_message('error', 'Session not found: ' . $session_id . ' for user: ' . $user_id);
            show_error('Session not found', 404);
        }
        
        // Check if current user is registered for this session
        if ($session->student_id !== $user_id) {
            log_message('error', 'Access denied for session: ' . $session_id . ' - Expected student: ' . $session->student_id . ', Got: ' . $user_id);
            show_error('You are not registered for this session', 403);
        }
        
        // Check if session time is valid (allow joining 5 minutes before start time)
        $current_time = time();
        $start_time = strtotime($session->start_time);
        $end_time = strtotime($session->end_time);
        
        // Allow joining 5 minutes (300 seconds) before session starts
        if ($current_time < ($start_time - 300)) {
            $this->session->set_flashdata('error', 'Session has not started yet. You can join 5 minutes before the scheduled time.');
            redirect('student/live_session');
        }
        
        // Check if session has ended
        if ($current_time > $end_time) {
            $this->session->set_flashdata('error', 'This session has already ended.');
            redirect('student/live_session');
        }
        
        // Mark attendance
        $attendance_marked = $this->Live_session_model->mark_attendance($session_id, $user_id);
        if (!$attendance_marked) {
            log_message('error', 'Failed to mark attendance for session: ' . $session_id . ', student: ' . $user_id);
        }
        
        // Log the join attempt
        log_message('info', 'Student ' . $user_id . ' joining session ' . $session_id . ' via link: ' . $session->meeting_link);
        
        // Redirect to Jitsi meeting
        if (!empty($session->meeting_link)) {
            redirect($session->meeting_link);
        } else {
            $this->session->set_flashdata('error', 'Meeting link is not available for this session.');
            redirect('student/live_session');
        }
    }
    
    /**
     * Get session details (AJAX endpoint for debugging)
     */
    public function get_session_details($session_id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('user_id');
        $session = $this->Live_session_model->get_session($session_id);
        
        $response = array(
            'session_found' => !empty($session),
            'user_id' => $user_id,
            'session_student_id' => $session ? $session->student_id : null,
            'access_granted' => $session && $session->student_id == $user_id,
            'meeting_link' => $session ? $session->meeting_link : null,
            'session_data' => $session
        );
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}