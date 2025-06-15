<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Progress extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Enrollment_model', 'Course_model']);
        $this->load->library('session');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    // View progress dashboard
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data = [
            'active_enrollments' => $this->Enrollment_model->get_student_enrollments($user_id),
            'completed_courses' => $this->Course_model->get_completed_courses($user_id),
            'title' => 'My Learning Progress'
        ];
        
        $this->load->view('templates/header', $data);
        $this->load->view('progress/index', $data);
        $this->load->view('templates/footer');
    }

    // Update progress after completing a lesson
    public function update($enrollment_id) {
        $enrollment = $this->Enrollment_model->get_enrollment($enrollment_id);
        
        // Verify ownership
        if (!$enrollment || $enrollment->student_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Invalid enrollment');
            redirect('dashboard');
        }
        
        // Calculate new progress (example: 10% per lesson)
        $total_lessons = $this->Course_model->count_lessons($enrollment->course_id);
        $progress_per_lesson = $total_lessons > 0 ? round(100/$total_lessons) : 0;
        $new_progress = min(100, $enrollment->progress + $progress_per_lesson);
        
        // Update progress or complete course
        if ($new_progress >= 100) {
            $this->Enrollment_model->complete_course($enrollment_id);
            $this->session->set_flashdata('success', 'Course completed!');
        } else {
            $this->Enrollment_model->update_progress($enrollment_id, $new_progress);
            $this->session->set_flashdata('success', 'Progress updated');
        }
        
        redirect('progress');
    }

    // Record course access
    public function record_access($enrollment_id) {
        $this->Enrollment_model->update_last_accessed($enrollment_id);
    }
}