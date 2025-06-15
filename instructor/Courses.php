<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Course_model', 'Instructor_model', 'Messages_model']);
        $this->load->library('session');
        
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
        $courses = $this->Instructor_model->get_instructor_courses($instructor_id);
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);
        
        $data = [
            'title' => 'My Courses',
            'courses' => $courses,
            'active_tab' => 'courses',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/templates/sidebar', $data);
        $this->load->view('instructor/courses/index', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function view($course_id = null) {
    $instructor_id = $this->session->userdata('user_id');  // Define here

    if (!$this->Instructor_model->is_instructor_assigned($course_id, $instructor_id)) {
        $this->session->set_flashdata('error', 'You are not assigned to this course');
        redirect('instructor/courses');
    }

    $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);

    $course = $this->Course_model->get_course($course_id);
    $students = $this->Instructor_model->get_course_students($course_id);
    $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);

    // Fetch conversation for this course and instructor
    $conversation = $this->Messages_model->get_conversation_by_course_and_instructor($course_id, $instructor_id);

    // If no conversation found, create empty object to avoid errors in view
    if (!$conversation) {
        $conversation = (object)[
            'conversation_id' => null,
            'enrollment_id' => null,
            'student_name' => 'N/A',
            'course_name' => $course->course_name ?? 'N/A'
        ];
        $messages = [];
    } else {
        $messages = $this->Messages_model->get_messages_by_conversation($conversation->conversation_id);
    }

    $data = [
        'title' => $course->course_name ?? 'Course Details',
        'course' => $course,
        'students' => $students,
        'active_tab' => 'courses',
        'unread_message_count' => $unread_message_count,
        'conversation' => $conversation,
        'messages' => $messages,
        'instructor_id' => $instructor_id
    ];

    $this->load->view('instructor/templates/header', $data);
    $this->load->view('instructor/templates/sidebar', $data);

    $this->load->view('instructor/courses/view', $data);
    $this->load->view('instructor/templates/footer');
}



}
?>