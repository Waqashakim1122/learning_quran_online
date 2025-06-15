<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Instructor_model', 'Messages_model']);
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
        $students = $this->Messages_model->get_assigned_students($instructor_id);
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'Messages',
            'students' => $students,
            'active_tab' => 'messages',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/templates/sidebar', $data);
        $this->load->view('instructor/messages/index', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function view($enrollment_id) {
        $instructor_id = $this->session->userdata('user_id');
        
        if (!$this->Instructor_model->is_student_assigned($enrollment_id, $instructor_id)) {
            log_message('error', 'Instructor not assigned to enrollment_id: ' . $enrollment_id . ', instructor_id: ' . $instructor_id);
            $this->session->set_flashdata('error', 'You are not assigned to this student');
            redirect('instructor/messages');
        }
        
        $enrollment = $this->db->get_where('enrollments', ['enrollment_id' => $enrollment_id, 'status' => 'active'])->row();
        if (!$enrollment) {
            log_message('error', 'No active enrollment found for enrollment_id: ' . $enrollment_id);
            $this->session->set_flashdata('error', 'Invalid enrollment');
            redirect('instructor/messages');
        }
        
        $conversation = $this->Messages_model->get_conversation_by_enrollment($enrollment_id);
        if (!$conversation) {
            $student = $this->db->select('u.name as student_name, u.id as student_id, c.course_name, c.course_id')
                ->from('enrollments e')
                ->join('users u', 'u.id = e.student_id')
                ->join('courses c', 'c.course_id = e.course_id')
                ->where('e.enrollment_id', $enrollment_id)
                ->get()->row();
            
            if (!$student) {
                log_message('error', 'Failed to fetch student for enrollment_id: ' . $enrollment_id);
                $this->session->set_flashdata('error', 'Invalid student or course data');
                redirect('instructor/messages');
            }
            
            $conversation_data = [
                'enrollment_id' => $enrollment_id,
                'student_id' => $student->student_id,
                'instructor_id' => $instructor_id,
                'course_id' => $student->course_id,
                'created_at' => date('Y-m-d H:i:s'),
                'last_message_at' => date('Y-m-d H:i:s')
            ];
            $conversation_id = $this->Messages_model->create_conversation($conversation_data);
            if (!$conversation_id) {
                log_message('error', 'Failed to create conversation for enrollment_id: ' . $enrollment_id . ', Data: ' . json_encode($conversation_data));
                $this->session->set_flashdata('error', 'Failed to start conversation');
                redirect('instructor/messages');
            }
            
            $conversation = $this->Messages_model->get_conversation($conversation_id);
            if (!$conversation) {
                log_message('error', 'Failed to fetch newly created conversation: ' . $conversation_id);
                $this->session->set_flashdata('error', 'Failed to load conversation');
                redirect('instructor/messages');
            }
        }
        
        $messages = $this->Messages_model->get_messages($conversation->conversation_id);
        if ($messages === false) {
            log_message('error', 'Failed to load messages for conversation_id: ' . $conversation->conversation_id . ', Error: ' . $this->db->error()['message']);
            $this->session->set_flashdata('error', 'Error loading messages');
            $messages = [];
        }
        
        $this->Messages_model->mark_messages_as_read($conversation->conversation_id, $instructor_id);
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'Chat with ' . ($conversation->student_name ?? 'Student'),
            'conversation' => $conversation,
            'messages' => $messages,
            'enrollment_id' => $enrollment_id,
            'instructor_id' => $instructor_id,
            'student_id' => $conversation->student_id,
            'active_tab' => 'messages',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/messages/view', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function reply($message_id) {
        $instructor_id = $this->session->userdata('user_id');
        $enrollment_id = $this->Messages_model->get_enrollment_id_by_message($message_id);
        
        if (!$enrollment_id) {
            log_message('error', 'No enrollment found for message_id: ' . $message_id);
            $this->session->set_flashdata('error', 'Invalid message');
            redirect('instructor/messages');
        }
        
        if (!$this->Instructor_model->is_student_assigned($enrollment_id, $instructor_id)) {
            log_message('error', 'Instructor not assigned to enrollment_id: ' . $enrollment_id . ', instructor_id: ' . $instructor_id);
            $this->session->set_flashdata('error', 'You are not assigned to this student');
            redirect('instructor/messages');
        }
        
        redirect('instructor/messages/view/' . $enrollment_id);
    }

    public function send() {
        $instructor_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('conversation_id', 'Conversation ID', 'required|integer');
        $this->form_validation->set_rules('enrollment_id', 'Enrollment ID', 'required|integer');
        $this->form_validation->set_rules('message_text', 'Message', 'required|trim|max_length[1000]');
        
        $enrollment_id = $this->input->post('enrollment_id');
        
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            log_message('error', 'Form validation failed: ' . $errors);
            $this->session->set_flashdata('error', $errors ?: 'Please fill in all required fields');
            redirect('instructor/messages/view/' . ($enrollment_id ?? ''));
        }
        
        $conversation_id = $this->input->post('conversation_id');
        
        log_message('debug', 'Send Message Attempt: enrollment_id=' . $enrollment_id . ', conversation_id=' . $conversation_id . ', instructor_id=' . $instructor_id);
        
        if (!$this->Instructor_model->is_student_assigned($enrollment_id, $instructor_id)) {
            log_message('error', 'Instructor not assigned to enrollment_id: ' . $enrollment_id . ', instructor_id=' . $instructor_id);
            $this->session->set_flashdata('error', 'You are not assigned to this student');
            redirect('instructor/messages');
        }
        
        $conversation = $this->Messages_model->get_conversation($conversation_id);
        if (!$conversation) {
            log_message('error', 'Conversation not found: conversation_id=' . $conversation_id);
            $this->session->set_flashdata('error', 'Conversation not found');
            redirect('instructor/messages/view/' . $enrollment_id);
        }
        
        if ($conversation->instructor_id != $instructor_id) {
            log_message('error', 'Invalid conversation ownership: conversation_id=' . $conversation_id . ', instructor_id=' . $instructor_id . ', conversation_instructor_id=' . $conversation->instructor_id);
            $this->session->set_flashdata('error', 'Invalid conversation');
            redirect('instructor/messages');
        }
        
        $recipient = $this->db->get_where('users', ['id' => $conversation->student_id])->row();
        if (!$recipient) {
            log_message('error', 'Recipient not found: student_id=' . $conversation->student_id);
            $this->session->set_flashdata('error', 'Invalid recipient');
            redirect('instructor/messages/view/' . $enrollment_id);
        }
        
        $message_data = [
            'conversation_id' => $conversation_id,
            'sender_id' => $instructor_id,
            'recipient_id' => $conversation->student_id,
            'message_text' => $this->security->xss_clean($this->input->post('message_text'))
        ];
        
        if ($this->Messages_model->send_message($message_data)) {
            $this->Messages_model->update_conversation_timestamp($conversation_id);
            $this->session->set_flashdata('success', 'Message sent successfully');
        } else {
            log_message('error', 'Failed to send message: ' . json_encode($message_data) . ', Error: ' . $this->db->error()['message']);
            $this->session->set_flashdata('error', 'Error sending message');
        }
        
        redirect('instructor/messages/view/' . $enrollment_id);
    }
}