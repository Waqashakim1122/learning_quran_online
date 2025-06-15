<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Course_model');
        $this->load->model('Instructor_model');
        $this->load->model('Student_model');
        $this->load->model('Enrollment_model');
        $this->_check_admin_access();
    }

    private function _check_admin_access() {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You are not authorized to access this page');
            redirect('login');
        }
    }

    public function index() {
        // Load all necessary data for the dashboard
        $data = $this->_get_dashboard_data();
        
  

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/dashboard/index', $data);
        $this->load->view('admin/templates/footer');
    }

    private function _get_dashboard_data() {
        $data = [
            'title' => 'Admin Dashboard',
            
            // User statistics
            'total_users' => $this->User_model->count_all_users(),
            'user_growth' => $this->User_model->calculate_monthly_growth(),
            
            // Student statistics
            'total_students' => $this->Student_model->get_active_students(),
            'student_growth' => $this->Student_model->get_student_growth(),
            'new_students' => $this->Student_model->get_new_students_this_month(),
            
            // Instructor statistics
            'total_instructors' => $this->Instructor_model->count_approved_instructors(),
            'instructor_growth' => $this->Instructor_model->calculate_instructor_growth(),
            'pending_instructors' => $this->Instructor_model->count_pending_instructors(),
            
            // Course statistics
            'total_courses' => $this->Course_model->get_total_courses(),
            'active_courses' => $this->Course_model->count_active_courses(),
            'new_courses' => $this->Course_model->get_courses_this_month(),
            
            // Enrollment statistics
            'total_enrollments' => $this->Enrollment_model->count_all_enrollments(),
            'active_enrollments' => $this->Enrollment_model->count_active_enrollments(),
            'pending_enrollments' => $this->Enrollment_model->count_pending_enrollments(),
            
            // Recent data
            'recent_students' => $this->Student_model->get_recent_students(5),
            'recent_instructors' => $this->Instructor_model->get_recent_instructors(5),
            'recent_courses' => $this->Course_model->get_recent_courses(5),
            'recent_enrollments' => $this->Enrollment_model->get_recent_enrollments(5),
            
            // Top performers
            'top_courses' => $this->Course_model->get_top_performing(3),
            'top_instructors' => $this->Instructor_model->get_top_instructors(3),
            
            // Charts data
            'chart_data' => [
                'users' => $this->_get_user_registration_data(),
                'enrollments' => $this->_get_enrollment_trend_data(),
                'revenue' => $this->_get_revenue_data() // If you have payment system
            ],
            
            // System status
            'system_status' => $this->_get_system_status()
        ];

        return $data;
    }

    private function _get_user_registration_data() {
        // Get user registration data for the last 30 days
        $this->db->select('DATE(created_at) as date, COUNT(*) as count');
        $this->db->from('users');
        $this->db->where('created_at >=', date('Y-m-d', strtotime('-30 days')));
        $this->db->group_by('DATE(created_at)');
        $this->db->order_by('date', 'ASC');
        $query = $this->db->get();
        
        $data = [];
        foreach ($query->result() as $row) {
            $data[] = [
                'date' => $row->date,
                'count' => $row->count
            ];
        }
        
        return $data;
    }

    private function _get_enrollment_trend_data() {
        // Get enrollment data for the last 30 days
        $this->db->select('DATE(enrolled_at) as date, COUNT(*) as count');
        $this->db->from('enrollments');
        $this->db->where('enrolled_at >=', date('Y-m-d', strtotime('-30 days')));
        $this->db->group_by('DATE(enrolled_at)');
        $this->db->order_by('date', 'ASC');
        $query = $this->db->get();
        
        $data = [];
        foreach ($query->result() as $row) {
            $data[] = [
                'date' => $row->date,
                'count' => $row->count
            ];
        }
        
        return $data;
    }

    private function _get_revenue_data() {
        // Placeholder - implement based on your payment system
        return [
            ['date' => date('Y-m-d', strtotime('-7 days')), 'amount' => 1500],
            ['date' => date('Y-m-d', strtotime('-6 days')), 'amount' => 1800],
            // ... add more data
        ];
    }

    private function _get_system_status() {
        // Basic system health check
        $status = [
            'database' => true, // You can add actual checks
            'storage' => [
                'used' => disk_total_space('/') - disk_free_space('/'),
                'total' => disk_total_space('/')
            ],
            'last_backup' => '2023-11-15 03:00:00', // Replace with actual
            // 'active_sessions' => $this->db->count_all('ci_sessions') // This line was removed/commented out
        ];
        
        return $status;
    }
}