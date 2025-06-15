<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
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

        $search = $this->input->get('search', TRUE);
        $role = $this->input->get('role', TRUE);

        $config['base_url'] = base_url('admin/users/index');
        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        $config['total_rows'] = $this->_count_active_users($search, $role);
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
        $offset = ($page - 1) * $config['per_page'];

        $users = $this->_get_active_users($config['per_page'], $offset, $search, $role);
        log_message('debug', 'Index users: ' . print_r($users, TRUE));

        $data = [
            'title' => 'Manage Active Users',
            'users' => $users,
            'pagination' => $this->pagination->create_links(),
            'search' => $search,
            'role' => $role
        ];

        // Load the standalone view
        $this->load->view('admin/users/index', $data);
    }

    public function view($user_id = null) {
        if (!$user_id || !is_numeric($user_id)) {
            log_message('error', 'Invalid user_id in view: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid user ID');
            redirect('admin/users');
        }

        $user = $this->User_model->get_user_with_details($user_id);
        log_message('debug', 'View user_id: ' . $user_id . ', User: ' . print_r($user, TRUE));

        if (!$user) {
            log_message('error', 'User not found for user_id: ' . $user_id);
            $this->session->set_flashdata('error', 'User not found');
            redirect('admin/users');
        }

        if (!$this->_is_user_active($user)) {
            log_message('error', 'User not active for user_id: ' . $user_id . ', Role: ' . ($user->role ?? 'N/A'));
            $this->session->set_flashdata('error', 'This user is not active yet (no enrollment or profile submission).');
            redirect('admin/users');
        }

        $data = [
            'title' => 'User Details',
            'user' => $user
        ];

        $this->load->view('admin/users/view', $data);
    }

    public function edit($user_id = null) {
        if (!$user_id || !is_numeric($user_id)) {
            log_message('error', 'Invalid user_id in edit: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid user ID');
            redirect('admin/users');
        }

        $this->load->library('form_validation');
        
        $user = $this->User_model->get_user_with_details($user_id);
        log_message('debug', 'Edit user_id: ' . $user_id . ', User: ' . print_r($user, TRUE));

        if (!$user) {
            log_message('error', 'User not found for user_id: ' . $user_id);
            $this->session->set_flashdata('error', 'User not found');
            redirect('admin/users');
        }

        if (!$this->_is_user_active($user)) {
            log_message('error', 'User not active for user_id: ' . $user_id . ', Role: ' . ($user->role ?? 'N/A'));
            $this->session->set_flashdata('error', 'This user is not active yet (no enrollment or profile submission).');
            redirect('admin/users');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];

        $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|max_length[100]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/users/edit', $data);
        } else {
            $update_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email')
            ];
            
            if ($this->User_model->update_user($user_id, $update_data)) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user');
            }
            
            redirect('admin/users/edit/' . $user_id);
        }
    }

    public function approve($user_id) {
        if (!$user_id || !is_numeric($user_id)) {
            log_message('error', 'Invalid user_id in approve: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid user ID');
            redirect('admin/users');
        }

        $user = $this->User_model->get_user_with_details($user_id);

        if (!$user || $user->role !== 'instructor') {
            log_message('error', 'Invalid instructor for user_id: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid instructor');
            redirect('admin/users');
        }

        $this->db->trans_start();
        $this->User_model->update_user($user_id, [
            'is_approved' => 1,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejected_at' => NULL,
            'rejection_reason' => NULL
        ]);
        $this->User_model->update_instructor_status($user_id, 'approved');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to approve instructor');
        } else {
            $this->session->set_flashdata('success', 'Instructor approved successfully');
        }

        redirect('admin/users/edit/' . $user_id);
    }

    public function reject($user_id) {
        if (!$user_id || !is_numeric($user_id)) {
            log_message('error', 'Invalid user_id in reject: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid user ID');
            redirect('admin/users');
        }

        $user = $this->User_model->get_user_with_details($user_id);

        if (!$user || $user->role !== 'instructor') {
            log_message('error', 'Invalid instructor for user_id: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid instructor');
            redirect('admin/users');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('reason', 'Rejection Reason', 'required|trim|max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/users/edit/' . $user_id);
        }

        $reason = $this->input->post('reason');

        $this->db->trans_start();
        $this->User_model->update_user($user_id, [
            'is_approved' => 0,
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason,
            'approved_at' => NULL
        ]);
        $this->User_model->update_instructor_status($user_id, 'rejected', $reason);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to reject instructor');
        } else {
            $this->session->set_flashdata('success', 'Instructor rejected successfully');
        }

        redirect('admin/users/edit/' . $user_id);
    }

    public function pending($user_id) {
        if (!$user_id || !is_numeric($user_id)) {
            log_message('error', 'Invalid user_id in pending: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid user ID');
            redirect('admin/users');
        }

        $user = $this->User_model->get_user_with_details($user_id);

        if (!$user || $user->role !== 'instructor') {
            log_message('error', 'Invalid instructor for user_id: ' . $user_id);
            $this->session->set_flashdata('error', 'Invalid instructor');
            redirect('admin/users');
        }

        $this->db->trans_start();
        $this->User_model->update_user($user_id, [
            'is_approved' => 0,
            'rejected_at' => NULL,
            'rejection_reason' => NULL,
            'approved_at' => NULL
        ]);
        $this->User_model->update_instructor_status($user_id, 'pending');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to set instructor to pending');
        } else {
            $this->session->set_flashdata('success', 'Instructor status set to pending successfully');
        }

        redirect('admin/users/edit/' . $user_id);
    }

    public function export() {
        $users = $this->_get_active_users(NULL, NULL, NULL, NULL);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="active_users_export_' . date('Y-m-d_H-i-s') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Registered', 'Enrolled Courses']);

        foreach ($users as $user) {
            $row = [
                $user->id,
                $user->name,
                $user->email,
                ucfirst($user->role),
                date('M j, Y', strtotime($user->created_at)),
                isset($user->enrolled_count) ? $user->enrolled_count : 'N/A'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    private function _get_active_users($limit = NULL, $offset = NULL, $search = NULL, $role = NULL) {
        $this->db->select('u.id, u.name, u.email, u.role, u.created_at');
        $this->db->select('(SELECT COUNT(*) FROM enrollments e WHERE e.student_id = u.id) as enrolled_count', FALSE);
        $this->db->from('users u');
        $this->db->group_start();
        $this->db->where('u.role', 'student');
        $this->db->where("EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = u.id)", NULL, FALSE);
        $this->db->or_group_start();
        $this->db->where('u.role', 'instructor');
        $this->db->where("EXISTS (SELECT 1 FROM instructor_profiles ip WHERE ip.user_id = u.id)", NULL, FALSE);
        $this->db->group_end();
        $this->db->group_end();

        if ($search) {
            $search = strip_tags($search);
            $this->db->group_start();
            $this->db->like('u.name', $search);
            $this->db->or_like('u.email', $search);
            $this->db->group_end();
        }

        if ($role) {
            $this->db->where('u.role', $role);
        }

        $this->db->order_by('u.created_at', 'DESC');

        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        log_message('debug', 'Active Users Query: ' . $this->db->last_query());
        log_message('debug', 'Active Users Count: ' . $query->num_rows());
        return $query->result();
    }

    private function _count_active_users($search = NULL, $role = NULL) {
        $this->db->from('users u');
        $this->db->group_start();
        $this->db->where('u.role', 'student');
        $this->db->where("EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = u.id)", NULL, FALSE);
        $this->db->or_group_start();
        $this->db->where('u.role', 'instructor');
        $this->db->where("EXISTS (SELECT 1 FROM instructor_profiles ip WHERE ip.user_id = u.id)", NULL, FALSE);
        $this->db->group_end();
        $this->db->group_end();

        if ($search) {
            $search = strip_tags($search);
            $this->db->group_start();
            $this->db->like('u.name', $search);
            $this->db->or_like('u.email', $search);
            $this->db->group_end();
        }

        if ($role) {
            $this->db->where('u.role', $role);
        }

        $query = $this->db->get();
        log_message('debug', 'Count Active Users Query: ' . $this->db->last_query());
        log_message('debug', 'Count Active Users: ' . $query->num_rows());
        return $query->num_rows();
    }

    private function _is_user_active($user) {
        if (!$user) {
            return false;
        }

        if ($user->role === 'student') {
            $this->db->select('1')
                     ->from('enrollments e')
                     ->where('e.student_id', $user->id);
            $result = $this->db->get()->num_rows() > 0;
            log_message('debug', 'Student active check for user_id: ' . $user->id . ', Result: ' . ($result ? 'Active' : 'Inactive'));
            return $result;
        } elseif ($user->role === 'instructor') {
            $this->db->select('1')
                     ->from('instructor_profiles ip')
                     ->where('ip.user_id', $user->id);
            $result = $this->db->get()->num_rows() > 0;
            log_message('debug', 'Instructor active check for user_id: ' . $user->id . ', Result: ' . ($result ? 'Active' : 'Inactive'));
            return $result;
        }

        log_message('debug', 'User role not student or instructor for user_id: ' . ($user->id ?? 'N/A'));
        return false;
    }
}