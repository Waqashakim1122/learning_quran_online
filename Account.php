<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

  public function pending() {
    // Check if user is logged in and is an instructor
    if (!$this->session->userdata('logged_in') || 
        $this->session->userdata('role') != 'instructor') {
        redirect('login');
    }

    // If instructor is approved, redirect to dashboard
    if ($this->session->userdata('is_approved')) {
        redirect('instructor/dashboard');
    }

    $user_id = $this->session->userdata('user_id');
    
    try {
        $data = [
            'title' => 'Account Pending Approval',
            'user' => $this->User_model->get_user($user_id)
        ];
        
        $this->load->view('templates/header', $data);
        $this->load->view('account/pending', $data);
        $this->load->view('templates/footer');
        
    } catch (Exception $e) {
        log_message('error', 'Account pending error: ' . $e->getMessage());
        show_error('An error occurred while loading the page.', 500);
    }
}
}