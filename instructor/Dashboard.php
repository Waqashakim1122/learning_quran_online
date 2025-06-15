<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model(['User_model', 'Course_model', 'Instructor_model', 'Student_model', 'Assignment_model', 'Messages_model']);
        
        // First check login status
        if (!$this->check_login()) {
            return; // Stop execution if not logged in
        }
        
        // Get the current method being called
        $current_method = $this->router->fetch_method();
        
        // Only check profile status for methods other than 'pending'
        if ($current_method !== 'pending') {
            $this->check_profile_status();
        }
    }

    private function check_login() {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'instructor') {
            $this->session->set_flashdata('error', 'Please login as instructor');
            redirect('auth/login');
            return false;
        }
        return true;
    }

    private function check_profile_status() {
        $user_id = $this->session->userdata('user_id');
        $profile = $this->Instructor_model->get_profile($user_id);
        
        if (!$profile) {
            $this->session->set_flashdata('error', 'Please submit your profile.');
            redirect('instructor/profile');
            return;
        }
        
        if ($profile->status !== 'approved') {
            redirect('instructor/dashboard/pending');
            return;
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $instructor_profile = $this->Instructor_model->get_profile($user_id);

        if (!$instructor_profile) {
            $this->session->set_flashdata('error', 'Instructor profile not found.');
            redirect('instructor/profile');
            return;
        }

        // Use user_id instead of profile id for instructor_id
        $instructor_id = $user_id;

        // Fetch instructor's courses
        $courses = $this->Instructor_model->get_instructor_courses($instructor_id);

        // Fetch students assigned to the instructor
        $students = $this->Instructor_model->get_assigned_students($instructor_id);

        // Fetch user details
        $user = $this->User_model->get_user($user_id);

        // Fetch unread message count
        $unread_message_count = $this->Messages_model->get_unread_message_count($instructor_id);

        $data = [
            'title' => 'Instructor Dashboard',
            'courses' => $courses,
            'students' => $students,
            'user' => $user,
            'active_tab' => 'dashboard',
            'unread_message_count' => $unread_message_count
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/templates/sidebar', $data);
        $this->load->view('instructor/dashboard/index', $data);
       
    }

    public function pending() {
        $user_id = $this->session->userdata('user_id');
        $profile = $this->Instructor_model->get_profile($user_id);

        if (!$profile) {
            $this->session->set_flashdata('error', 'Please submit your profile.');
            redirect('instructor/profile');
            return;
        }

        // If the profile is approved, redirect to the dashboard
        if ($profile->status === 'approved') {
            redirect('instructor/dashboard');
            return;
        }

        // Fetch user details
        $user = $this->User_model->get_user($user_id);

        // Fetch unread message count
        $unread_message_count = $this->Messages_model->get_unread_message_count($user_id);

        $data = [
            'title' => 'Profile Approval Pending',
            'profile' => $profile,
            'user' => $user,
            'active_tab' => 'pending',
            'unread_message_count' => $unread_message_count
        ];

        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/dashboard/pending', $data);
        $this->load->view('instructor/templates/footer');
    }
}