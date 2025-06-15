<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instructors extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Course_model');
        $this->load->model('Instructor_model'); // Ensure this model is loaded
        $this->_check_admin_access();
    }

    private function _check_admin_access() {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You are not authorized to access this page');
            redirect('login');
        }
    }

    public function index() {
        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/instructors');
        $config['total_rows'] = $this->User_model->count_pending_instructors();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data = [
            'title' => 'Instructor Management Dashboard',
            'pending_count' => $this->User_model->count_pending_instructors(),
            'approved_count' => $this->Instructor_model->count_approved_instructors(),
            'suspended_count' => $this->Instructor_model->count_suspended_instructors(),
            'active_tab' => 'pending',
            'pending_instructors' => $this->User_model->get_pending_instructors($config['per_page'], $page),
            'approved_instructors' => $this->Instructor_model->get_approved_instructors($config['per_page'], 0),
            'suspended_instructors' => $this->Instructor_model->get_suspended_instructors($config['per_page'], 0),
            'pagination' => $this->pagination->create_links()
        ];

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/instructors/dashboard', $data);
       
    }

    public function pending($page = 0) {
        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/instructors/pending');
        $config['total_rows'] = $this->User_model->count_pending_instructors();
        $config['per_page'] = 10;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $data = [
            'title' => 'Pending Instructor Profiles',
            'pending_count' => $this->User_model->count_pending_instructors(),
            'approved_count' => $this->Instructor_model->count_approved_instructors(),
            'suspended_count' => $this->Instructor_model->count_suspended_instructors(),
            'active_tab' => 'pending',
            'pending_instructors' => $this->User_model->get_pending_instructors($config['per_page'], $page),
            'approved_instructors' => $this->Instructor_model->get_approved_instructors($config['per_page'], 0),
            'suspended_instructors' => $this->Instructor_model->get_suspended_instructors($config['per_page'], 0),
            'pagination' => $this->pagination->create_links()
        ];

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/instructors/dashboard', $data);
        
    }

    public function approved($page = 0) {
        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/instructors/approved');
        $config['total_rows'] = $this->Instructor_model->count_approved_instructors();
        $config['per_page'] = 10;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $data = [
            'title' => 'Approved Instructors',
            'pending_count' => $this->User_model->count_pending_instructors(),
            'approved_count' => $this->Instructor_model->count_approved_instructors(),
            'suspended_count' => $this->Instructor_model->count_suspended_instructors(),
            'active_tab' => 'approved',
            'pending_instructors' => $this->User_model->get_pending_instructors($config['per_page'], 0),
            'approved_instructors' => $this->Instructor_model->get_approved_instructors($config['per_page'], $page),
            'suspended_instructors' => $this->Instructor_model->get_suspended_instructors($config['per_page'], 0),
            'pagination' => $this->pagination->create_links()
        ];

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/instructors/dashboard', $data);
       
    }

    public function suspend($user_id) {
        $instructor = $this->User_model->get_user_with_details($user_id);

        if (!$instructor || $instructor->role != 'instructor') {
            $this->session->set_flashdata('error', 'Instructor not found');
            redirect('admin/instructors/pending');
        }

        $this->db->trans_start();

        // Update user status
        $user_updated = $this->User_model->update_user($user_id, [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Deactivate associated courses
        $courses_deactivated = $this->Course_model->deactivate_instructor_courses($user_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$user_updated || !$courses_deactivated) {
            $this->session->set_flashdata('error', 'Failed to suspend instructor');
        } else {
            $this->session->set_flashdata('success', 'Instructor suspended successfully');
        }

        redirect('admin/instructors/view/' . $user_id);
    }

    public function activate($user_id) {
        $instructor = $this->User_model->get_user_with_details($user_id);

        if (!$instructor || $instructor->role != 'instructor') {
            $this->session->set_flashdata('error', 'Instructor not found');
            redirect('admin/instructors/pending');
        }

        $this->db->trans_start();

        // Update user status
        $user_updated = $this->User_model->update_user($user_id, [
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Activate associated courses
        $courses_activated = $this->Course_model->activate_instructor_courses($user_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$user_updated || !$courses_activated) {
            $this->session->set_flashdata('error', 'Failed to activate instructor');
        } else {
            $this->session->set_flashdata('success', 'Instructor activated successfully');
        }

        redirect('admin/instructors/view/' . $user_id);
    }

    public function approve($user_id) {
        $this->db->trans_start();

        $this->User_model->update_instructor_status($user_id, 'approved');

        $this->User_model->update_user($user_id, [
            'is_approved' => 1,
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->where('instructor_id', $user_id);
        $this->db->where('approval_status', 'pending');
        $this->db->update('courses', [
            'approval_status' => 'approved',
            'status' => 'published',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to approve instructor profile');
        } else {
            $this->session->set_flashdata('success', 'Instructor profile approved successfully');
        }

        redirect('admin/instructors/pending');
    }

    public function reject($user_id) {
        $this->form_validation->set_rules('reason', 'Feedback', 'required|trim|max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/instructors/view/' . $user_id);
        }

        $reason = $this->input->post('reason');

        $this->db->trans_start();

        $this->User_model->update_instructor_status($user_id, 'rejected', $reason);

        $this->User_model->update_user($user_id, [
            'is_approved' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to reject instructor profile');
        } else {
            $this->session->set_flashdata('success', 'Instructor profile rejected successfully');
        }

        redirect('admin/instructors/pending');
    }

    public function view($user_id) {
        $data = [
            'title' => 'Instructor Profile Review',
            'instructor' => $this->User_model->get_user_with_details($user_id),
            'performance' => $this->User_model->get_instructor_performance($user_id),
            'courses' => $this->Course_model->get_instructor_courses($user_id)
        ];

        if (!$data['instructor'] || $data['instructor']->role != 'instructor') {
            $this->session->set_flashdata('error', 'Instructor not found');
            redirect('admin/instructors/pending');
        }

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/instructors/view', $data);
       
    }
}