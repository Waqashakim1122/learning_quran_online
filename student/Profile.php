<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // Restrict access to students only
        if ($this->session->userdata('role') !== 'student') {
            show_error('You are not authorized to access this page', 403);
        }
        // Load required libraries and models
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('Enrollment_model');
    }

    public function index() {
        $data['title'] = 'My Profile';
        $user_id = $this->session->userdata('user_id');
        log_message('debug', 'User ID in Profile: ' . $user_id);

        // Fetch user data
        $data['user'] = $this->db->get_where('users', [
            'id' => $user_id,
            'role' => 'student'
        ])->row();
        log_message('debug', 'User Data: ' . print_r($data['user'], TRUE));

        if (!$data['user']) {
            $this->session->set_flashdata('error', 'Profile not found.');
            redirect('student/dashboard');
        }

        // Fetch enrolled courses
        $data['enrolled_courses'] = $this->Enrollment_model->get_student_courses($user_id);
        $data['active_tab'] = 'profile';

        // Load views
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/dashboard/profile', $data);
        $this->load->view('student/templates/footer');
    }

    public function settings() {
        $data['title'] = 'Settings';
        $user_id = $this->session->userdata('user_id');

        // Fetch user data with required columns
        $this->db->select('id, name, email, phone, bio, profile_image');
        $data['user'] = $this->db->get_where('users', [
            'id' => $user_id,
            'role' => 'student'
        ])->row();

        if (!$data['user']) {
            $this->session->set_flashdata('error', 'Profile not found.');
            redirect('student/profile');
        }

        $data['active_tab'] = 'settings';

        // Load views
        $this->load->view('student/templates/header', $data);
        $this->load->view('student/templates/sidebar', $data);
        $this->load->view('student/profile/settings', $data);
        $this->load->view('student/templates/footer');
    }

    public function update() {
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|regex_match[/^[0-9]{10,15}$/]');
        $this->form_validation->set_rules('bio', 'Bio', 'trim|max_length[500]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('student/profile/settings');
        }

        $user_id = $this->session->userdata('user_id');
        $update_data = [
            'name' => $this->input->post('full_name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'bio' => $this->input->post('bio'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $user_id);
        $this->db->update('users', $update_data);

        $this->session->set_flashdata('success', 'Profile updated successfully!');
        redirect('student/profile/settings');
    }

    public function upload_image() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->session->set_flashdata('error', 'You must be logged in to upload a profile image.');
            redirect('student/profile/settings');
        }

        // Configure file upload
        $config['upload_path'] = 'D:/Web Development 7th/XAMPP/htdocs/learning_quran_online/assets/upload/student_imges/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'profile_' . $user_id . '_' . time();
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('profile_image')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $this->db->where('id', $user_id);
            $this->db->update('users', ['profile_image' => $data['file_name']]);
            $this->session->set_flashdata('success', 'Profile image updated successfully!');
        }
        redirect('student/profile/settings');
    }

    public function remove_image() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => $user_id])->row();

        if ($user->profile_image) {
            $file_path = 'D:/Web Development 7th/XAMPP/htdocs/learning_quran_online/assets/upload/student_imges/' . $user->profile_image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $this->db->where('id', $user_id);
            $this->db->update('users', ['profile_image' => null]);
            $this->session->set_flashdata('success', 'Profile image removed successfully!');
        }
        redirect('student/profile/settings');
    }

    public function change_password() {
        // Set validation rules
        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('student/profile/settings');
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', [
            'id' => $user_id,
            'role' => 'student'
        ])->row();

        // Verify current password
        if (!password_verify($this->input->post('current_password'), $user->password)) {
            $this->session->set_flashdata('error', 'Current password is incorrect.');
            redirect('student/profile/settings');
        }

        $update_data = [
            'password' => password_hash($this->input->post('new_password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update password
        $this->db->where('id', $user_id);
        $this->db->update('users', $update_data);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Password changed successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to change password.');
        }
        redirect('student/profile/settings');
    }
}