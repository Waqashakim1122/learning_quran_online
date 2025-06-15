<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Messages_model']);
        $this->load->library(['session', 'form_validation']);
        
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'student') {
            $this->session->set_flashdata('error', 'Please login as student');
            redirect('auth/login');
        }
    }

    public function index() {
        $student_id = $this->session->userdata('user_id');
        $instructors = $this->Messages_model->get_assigned_instructors($student_id);
        $unread_message_count = $this->Messages_model->get_unread_message_count($student_id);
        
        $data = [
            'title' => 'Messages',
            'instructors' => $instructors,
            'active_tab' => 'messages',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/messages/index', $data);
        $this->load->view('student/templates/footer');
    }

    public function inbox() {
        $this->index();
    }

    public function view($enrollment_id) {
        $student_id = $this->session->userdata('user_id');
        
        $enrollment = $this->db->get_where('enrollments', ['enrollment_id' => $enrollment_id, 'student_id' => $student_id, 'status' => 'active'])->row();
        if (!$enrollment) {
            log_message('error', 'No active enrollment found for enrollment_id: ' . $enrollment_id . ', student_id: ' . $student_id);
            $this->session->set_flashdata('error', 'Invalid enrollment');
            redirect('student/messages');
        }
        
        $conversation = $this->Messages_model->get_conversation_by_enrollment($enrollment_id);
        if (!$conversation) {
            $instructor = $this->db->select('u.name as instructor_name, u.id as instructor_id, c.course_name, c.course_id')
                ->from('enrollments e')
                ->join('users u', 'u.id = e.assigned_instructor_id')
                ->join('courses c', 'c.course_id = e.course_id')
                ->where('e.enrollment_id', $enrollment_id)
                ->get()->row();
            
            if (!$instructor) {
                log_message('error', 'Failed to fetch instructor for enrollment_id: ' . $enrollment_id);
                $this->session->set_flashdata('error', 'Invalid instructor or course data');
                redirect('student/messages');
            }
            
            $conversation_data = [
                'enrollment_id' => $enrollment_id,
                'student_id' => $student_id,
                'instructor_id' => $instructor->instructor_id,
                'course_id' => $instructor->course_id,
                'created_at' => date('Y-m-d H:i:s'),
                'last_message_at' => date('Y-m-d H:i:s')
            ];
            $conversation_id = $this->Messages_model->create_conversation($conversation_data);
            if (!$conversation_id) {
                log_message('error', 'Failed to create conversation for enrollment_id: ' . $enrollment_id . ', Data: ' . json_encode($conversation_data));
                $this->session->set_flashdata('error', 'Failed to start conversation');
                redirect('student/messages');
            }
            
            $conversation = $this->Messages_model->get_conversation_for_student($conversation_id);
            if (!$conversation) {
                log_message('error', 'Failed to fetch newly created conversation: ' . $conversation_id);
                $this->session->set_flashdata('error', 'Failed to load conversation');
                redirect('student/messages');
            }
        }
        
        $messages = $this->Messages_model->get_messages($conversation->conversation_id);
        if ($messages === false) {
            log_message('error', 'Failed to load messages for conversation_id: ' . $conversation->conversation_id . ', Error: ' . $this->db->error()['message']);
            $this->session->set_flashdata('error', 'Error loading messages');
            $messages = [];
        }
        
        $this->Messages_model->mark_messages_as_read($conversation->conversation_id, $student_id);
        $unread_message_count = $this->Messages_model->get_unread_message_count($student_id);
        
        $data = [
            'title' => 'Chat with ' . ($conversation->instructor_name ?? 'Instructor'),
            'conversation' => $conversation,
            'messages' => $messages,
            'enrollment_id' => $enrollment_id,
            'student_id' => $student_id,
            'active_tab' => 'messages',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/messages/view', $data);
        $this->load->view('student/templates/footer');
    }

    public function send() {
        $student_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('conversation_id', 'Conversation ID', 'required|integer');
        $this->form_validation->set_rules('enrollment_id', 'Enrollment ID', 'required|integer');
        $this->form_validation->set_rules('message_text', 'Message', 'required|trim|max_length[1000]');
        
        $enrollment_id = $this->input->post('enrollment_id');
        
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            log_message('error', 'Form validation failed: ' . $errors);
            $this->session->set_flashdata('error', $errors ?: 'Please fill in all required fields');
            redirect('student/messages/view/' . ($enrollment_id ?? ''));
        }
        
        $conversation_id = $this->input->post('conversation_id');
        
        $enrollment = $this->db->get_where('enrollments', ['enrollment_id' => $enrollment_id, 'student_id' => $student_id, 'status' => 'active'])->row();
        if (!$enrollment) {
            log_message('error', 'Invalid enrollment for student: enrollment_id=' . $enrollment_id . ', student_id=' . $student_id);
            $this->session->set_flashdata('error', 'Invalid enrollment');
            redirect('student/messages');
        }
        
        $conversation = $this->Messages_model->get_conversation_for_student($conversation_id);
        if (!$conversation || $conversation->student_id != $student_id) {
            log_message('error', 'Invalid conversation: conversation_id=' . $conversation_id . ', student_id=' . $student_id);
            $this->session->set_flashdata('error', 'Invalid conversation');
            redirect('student/messages');
        }
        
        $recipient = $this->db->get_where('users', ['id' => $conversation->instructor_id])->row();
        if (!$recipient) {
            log_message('error', 'Recipient not found: instructor_id=' . $conversation->instructor_id);
            $this->session->set_flashdata('error', 'Invalid recipient');
            redirect('student/messages/view/' . $enrollment_id);
        }
        
        $message_data = [
            'conversation_id' => $conversation_id,
            'sender_id' => $student_id,
            'recipient_id' => $conversation->instructor_id,
            'message_text' => $this->security->xss_clean($this->input->post('message_text'))
        ];
        
        if ($this->Messages_model->send_message($message_data)) {
            $this->Messages_model->update_conversation_timestamp($conversation_id);
            $this->session->set_flashdata('success', 'Message sent successfully');
        } else {
            log_message('error', 'Failed to send message: ' . json_encode($message_data) . ', Error: ' . $this->db->error()['message']);
            $this->session->set_flashdata('error', 'Error sending message');
        }
        
        redirect('student/messages/view/' . $enrollment_id);
    }
}