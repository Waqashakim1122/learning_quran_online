<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model');
       $this->load->helper('url'); // Load URL helper
        $this->load->helper('cookie'); // Load cookie helper explicitly
    }

    private function _check_session_and_redirect() {
        $current_url = current_url();
        if ($current_url === base_url('instructor/dashboard') || $current_url === base_url('instructor/dashboard/pending') || $current_url === base_url('instructor/profile')) {
            return;
        }

        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            log_message('debug', 'Session role: ' . $role);
            if (!in_array($role, ['admin', 'instructor', 'student'])) {
                log_message('error', 'Invalid role detected: ' . $role);
                $this->session->sess_destroy();
                $this->session->set_flashdata('error', 'We encountered an issue with your session. Please log in again.');
                redirect('auth/login');
                return;
            }
            switch ($role) {
                case 'admin':
                    redirect('admin/dashboard');
                    break;
                case 'instructor':
                    $this->load->model('Instructor_model');
                    $user_id = $this->session->userdata('user_id');
                    $profile = $this->Instructor_model->get_profile($user_id);
                    if (!$profile) {
                        redirect('instructor/profile');
                    } elseif ($profile->status !== 'approved') {
                        redirect('instructor/dashboard/pending');
                    } else {
                        redirect('instructor/dashboard');
                    }
                    break;
                case 'student':
                    redirect('student/dashboard');
                    break;
            }
        }

        // Check for "Remember Me" cookie
        $remember_token = get_cookie('remember_me');
        if ($remember_token) {
            $user = $this->User_model->get_user_by_remember_token($remember_token);
            if ($user) {
                $session_data = [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'full_name' => isset($user->name) ? $user->name : $user->email,
                    'logged_in' => true
                ];
                $this->session->set_userdata($session_data);
                log_message('debug', 'Auto-login via Remember Me for user ID: ' . $user->id);
                redirect($this->_get_role_redirect($user->role));
            }
        }
    }

    private function _get_role_redirect($role) {
        $this->load->model('Instructor_model');
        switch ($role) {
            case 'admin':
                return 'admin/dashboard';
            case 'instructor':
                $user_id = $this->session->userdata('user_id') ?: $this->User_model->get_user_by_email(get_cookie('remember_me_email'))->id;
                $profile = $this->Instructor_model->get_profile($user_id);
                if (!$profile) return 'instructor/profile';
                elseif ($profile->status !== 'approved') return 'instructor/dashboard/pending';
                else return 'instructor/dashboard';
            case 'student':
                return 'student/dashboard';
            default:
                return 'auth/login';
        }
    }

    public function index() {
        redirect('auth/login');
    }

    public function login() {
        $this->_check_session_and_redirect();

        log_message('debug', 'Login method accessed');

        $data = [
            'title' => 'Login - Learning Quran Online',
            'view' => 'auth/login'
        ];

        $this->_handle_auth_flow($data, function() {
            log_message('debug', 'Login callback started');
            
            $email = $this->input->post('email', true);
            $password = $this->input->post('password');
            $remember_me = $this->input->post('remember_me') ? true : false;

            $login_result = $this->User_model->login($email, $password, $remember_me);
            
            if ($login_result === false) {
                log_message('debug', 'Login failed for email: ' . $email);
                return false;
            } elseif (is_string($login_result)) {
                $this->session->set_flashdata('error', $login_result);
                return false;
            }

            log_message('debug', 'Login successful for user: ' . print_r($login_result, true));
            return $login_result;
        }, 'auth/login', '', true);
    }

    private function _handle_auth_flow($data, $auth_callback, $view, $redirect = '', $set_session = false) {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {
                $result = $auth_callback();
                
                if ($result) {
                    if ($set_session) {
                        $session_data = [
                            'user_id' => $result->id,
                            'email' => $result->email,
                            'role' => $result->role,
                            'full_name' => isset($result->name) ? $result->name : $result->email,
                            'logged_in' => true
                        ];
                        $this->session->set_userdata($session_data);
                        log_message('debug', 'Session set: ' . print_r($session_data, TRUE));
                        
                        switch ($result->role) {
                            case 'admin':
                                redirect('admin/dashboard');
                                break;
                            case 'instructor':
                                $this->load->model('Instructor_model');
                                $profile = $this->Instructor_model->get_profile($result->id);
                                if (!$profile) {
                                    redirect('instructor/profile');
                                } elseif ($profile->status !== 'approved') {
                                    redirect('instructor/dashboard/pending');
                                } else {
                                    redirect('instructor/dashboard');
                                }
                                break;
                            case 'student':
                                redirect('student/dashboard');
                                break;
                            default:
                                $this->session->set_flashdata('error', 'We apologize, but your account role is not recognized. Please contact support.');
                                redirect('auth/login');
                                break;
                        }
                        return;
                    }
                    
                    if ($redirect) {
                        $this->session->set_flashdata('success', 'Your request has been processed successfully.');
                        redirect($redirect);
                        return;
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Please ensure all fields are correctly filled. ' . validation_errors());
            }
        }
        
       
        $this->load->view($view, $data);
        
    }

    public function register() {
        $this->_check_session_and_redirect();

        $data = [
            'title' => 'Register - Learning Quran Online',
            'view' => 'auth/register'
        ];

        $this->_handle_auth_flow($data, function() {
            $this->form_validation->set_rules('full_name', 'Full Name', 'required|min_length[2]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[student,instructor]');

            if (!$this->form_validation->run()) {
                log_message('error', 'Validation failed: ' . validation_errors());
                $this->session->set_flashdata('error', 'Please correct the following issues: ' . validation_errors());
                return false;
            }

            $user_data = [
                'name' => $this->input->post('full_name', true),
                'email' => $this->input->post('email', true),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role' => $this->input->post('role', true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'is_approved' => ($this->input->post('role', true) == 'student') ? 1 : 0,
                'approval_status' => ($this->input->post('role', true) == 'student') ? 'approved' : 'pending',
                'is_active' => 1
            ];

            if ($this->User_model->register($user_data)) {
                log_message('debug', 'User registered successfully');
                $this->session->set_flashdata('success', 'Thank you for registering. Please log in to proceed.');
                return true;
            }
            log_message('error', 'Registration failed in User_model->register');
            $this->session->set_flashdata('error', 'We encountered an issue during registration. Please try again later or contact support.');
            return false;
        }, 'auth/register', 'auth/login');
    }

    public function logout() {
        $this->session->unset_userdata(['user_id', 'email', 'role', 'full_name', 'logged_in']);
        delete_cookie('remember_me');
        delete_cookie('remember_me_email');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been successfully logged out. We look forward to seeing you again.');
        redirect('auth/login');
    }

    public function forgot_password() {
        $data = [
            'title' => 'Forgot Password - Learning Quran Online',
            'view' => 'auth/forgot_password'
        ];

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            
            if ($this->form_validation->run()) {
                $email = $this->input->post('email', true);
                $user = $this->User_model->get_user_by_email($email);
                
                if ($user) {
                    $reset_token = bin2hex(random_bytes(32));
                    // Implement save_reset_token and send_reset_email
                    // $this->User_model->save_reset_token($user->id, $reset_token);
                    // $this->_send_reset_email($email, $reset_token);
                    
                    $this->session->set_flashdata('success', 'A password reset link has been sent to your email address. Please check your inbox.');
                } else {
                    $this->session->set_flashdata('error', 'The email address provided is not associated with any account. Please verify and try again.');
                }
                redirect('auth/forgot_password');
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('auth/forgot_password', $data);
        $this->load->view('templates/footer');
    }

    public function check_auth() {
        $response = [
            'authenticated' => $this->session->userdata('logged_in') ? true : false,
            'role' => $this->session->userdata('role')
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}