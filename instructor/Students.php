<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Student_model', 'Instructor_model', 'Course_model']);
        $this->load->library('session');
        
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'instructor') {
            redirect('auth/login');
        }
        
        $this->check_profile_status();
    }
    
    private function check_profile_status() {
        $profile = $this->Instructor_model->get_profile($this->session->userdata('user_id'));
        if (!$profile || $profile->status !== 'approved') {
            redirect('instructor/dashboard/pending');
        }
    }

    public function index() {
        $instructor_id = $this->session->userdata('user_id');
        $students = $this->Instructor_model->get_assigned_students($instructor_id);
        
        $data = [
            'title' => 'My Students',
            'students' => $students,
            'active_tab' => 'students'
        ];
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/templates/sidebar', $data);
        $this->load->view('instructor/students/index', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function profile($enrollment_id) {
        $instructor_id = $this->session->userdata('user_id');
        
        // Verify the instructor is assigned to this student
        if (!$this->Instructor_model->is_student_assigned($enrollment_id, $instructor_id)) {
            $this->session->set_flashdata('error', 'You are not assigned to this student');
            redirect('instructor/students');
        }
        
        $student_profile = $this->Student_model->get_student_by_enrollment($enrollment_id);
        $progress_data = $this->Student_model->get_student_progress($enrollment_id);
        $upcoming_classes = $this->Student_model->get_upcoming_classes($enrollment_id);
        $completed_assignments = $this->Student_model->get_completed_assignments($enrollment_id);
        
        $data = [
            'title' => 'Student Profile - ' . $student_profile->student_name,
            'student' => $student_profile,
            'progress' => $progress_data,
            'upcoming_classes' => $upcoming_classes,
            'completed_assignments' => $completed_assignments,
            'active_tab' => 'students'
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/students/profile', $data);
        $this->load->view('instructor/templates/footer');
    }

    public function schedule_class($enrollment_id) {
        $instructor_id = $this->session->userdata('user_id');
        
        if (!$this->Instructor_model->is_student_assigned($enrollment_id, $instructor_id)) {
            $this->session->set_flashdata('error', 'You are not assigned to this student');
            redirect('instructor/students');
        }
        
        $student = $this->Student_model->get_student_by_enrollment($enrollment_id);
        $instructor_availability = $this->Instructor_model->get_instructor_availability($instructor_id);
        
        $data = [
            'title' => 'Schedule Class',
            'student' => $student,
            'availability' => $instructor_availability,
            'active_tab' => 'students'
        ];
        
        $this->load->view('instructor/templates/header', $data);
        $this->load->view('instructor/students/schedule_class', $data);
        $this->load->view('instructor/templates/footer');
    }
}