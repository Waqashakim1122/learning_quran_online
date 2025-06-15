<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Instructor_model']);
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);

        // Check login but don't redirect if we're on login page
        $current_method = $this->router->fetch_method();
        if ($current_method != 'login' && (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'instructor')) {
            $this->session->set_flashdata('error', 'Please log in as an instructor to access this page.');
            redirect('auth/login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            log_message('error', 'No user_id found in session');
            $this->session->set_flashdata('error', 'Session error. Please log in again.');
            redirect('auth/login');
        }

        $profile = $this->Instructor_model->get_profile($user_id);
        if ($profile && $profile->status === 'approved') {
            redirect('instructor/dashboard');
        }

        $data = [
            'title' => 'Instructor Profile - Learning Quran Online',
            'profile' => $profile ?: null,
            'is_approved' => $this->session->userdata('is_approved') ?? 0,
            'active_tab' => $this->session->flashdata('active_tab') ?: 'personal',
            'errors' => $this->session->flashdata('errors') ?: [],
            'form_data' => $this->session->flashdata('form_data') ?: ($profile ? (array)$profile : [])
        ];

        $this->load->view('templates/header', $data);
         $this->load->view('instructor/templates/navbar', $data);
        $this->load->view('instructor/dashboard/profile', $data);
        $this->load->view('templates/footer');
    }

    public function view() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            log_message('error', 'No user_id found in session for profile view');
            $this->session->set_flashdata('error', 'Session error. Please log in again.');
            redirect('auth/login');
        }

        $profile = $this->Instructor_model->get_profile($user_id);
        if (!$profile) {
            $this->session->set_flashdata('error', 'Profile not found. Please submit your profile.');
            redirect('instructor/profile');
        }

        $data = [
            'title' => 'View Instructor Profile - Learning Quran Online',
            'profile' => $profile,
            'is_approved' => $this->session->userdata('is_approved') ?? 0,
            'active_tab' => 'view'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('instructor/dashboard/profile_view', $data);
        $this->load->view('templates/footer');
    }

    public function save() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            log_message('error', 'No user_id found in session during profile save');
            $this->session->set_flashdata('error', 'Session error. Please log in again.');
            redirect('auth/login');
        }

        log_message('debug', 'Profile save called. POST: ' . json_encode($_POST));
        log_message('debug', 'FILES: ' . json_encode($_FILES));

        $existing_profile = $this->Instructor_model->get_profile($user_id);
        if ($existing_profile && $existing_profile->status !== 'rejected') {
            $this->session->set_flashdata('error', 'Profile already submitted. Await approval or edit after rejection.');
            redirect('instructor/dashboard/pending');
        }

        // Validation rules
        $this->form_validation->set_rules($this->_get_validation_rules(!$existing_profile));

        $upload_path = './Uploads/Instructors/';
        $upload_data = [];
        $file_errors = [];

        // Create upload directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
            chmod($upload_path, 0777);
        }

        // Handle file uploads
        $files = ['profile_picture', 'cv', 'degree', 'id_proof_front', 'id_proof_back', 'additional_certs'];
        if (!$existing_profile) {
            foreach (['profile_picture', 'cv', 'degree'] as $field) {
                if (empty($_FILES[$field]['name'])) {
                    $file_errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
                }
            }
        }

        foreach ($files as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => $field === 'profile_picture' ? 'jpg|jpeg|png' : 'pdf|doc|docx|jpg|jpeg|png',
                    'max_size' => $field === 'profile_picture' ? 2048 : 5120,
                    'encrypt_name' => true
                ];

                $this->upload->initialize($config);

                if ($field === 'additional_certs' && is_array($_FILES[$field]['name'])) {
                    $uploaded_files = [];
                    foreach ($_FILES[$field]['name'] as $key => $name) {
                        if (!empty($name)) {
                            $_FILES['temp_file'] = [
                                'name' => $_FILES[$field]['name'][$key],
                                'type' => $_FILES[$field]['type'][$key],
                                'tmp_name' => $_FILES[$field]['tmp_name'][$key],
                                'error' => $_FILES[$field]['error'][$key],
                                'size' => $_FILES[$field]['size'][$key]
                            ];
                            if ($this->upload->do_upload('temp_file')) {
                                $uploaded_files[] = $this->upload->data('file_name');
                            } else {
                                $file_errors[$field] = $this->upload->display_errors();
                            }
                        }
                    }
                    $upload_data[$field] = !empty($uploaded_files) ? json_encode($uploaded_files) : null;
                } elseif ($this->upload->do_upload($field)) {
                    $upload_data[$field] = $this->upload->data('file_name');
                } else {
                    $file_errors[$field] = $this->upload->display_errors();
                }
            }
        }

        $errors = array_merge($this->form_validation->error_array(), $file_errors);

        if ($this->form_validation->run() === false || !empty($file_errors)) {
            log_message('error', 'Validation errors: ' . json_encode($errors));
            $this->session->set_flashdata('errors', $errors);
            $this->session->set_flashdata('form_data', $this->input->post());
            $this->session->set_flashdata('active_tab', $this->input->post('active_tab') ?: 'documents');
            redirect('instructor/profile');
        }

        // Prepare data for database
        $data = [
            'user_id' => $user_id,
            'name' => $this->input->post('name', true),
            'gender' => $this->input->post('gender', true),
            'dob' => $this->input->post('dob', true),
            'phone_number' => $this->input->post('phone_number', true),
            'languages' => $this->input->post('languages', true),
            'bio' => $this->input->post('bio', true),
            'education' => $this->input->post('education', true),
            'video_intro' => $this->input->post('video_intro', true),
            'experience' => $this->input->post('experience', true),
            'specialization' => $this->input->post('specialization', true),
            'teaching_methodology' => $this->input->post('teaching_methodology', true) ?: null,
            'profile_picture_path' => $upload_data['profile_picture'] ?? ($existing_profile ? $existing_profile->profile_picture_path : null),
            'cv_path' => $upload_data['cv'] ?? ($existing_profile ? $existing_profile->cv_path : null),
            'degree_path' => $upload_data['degree'] ?? ($existing_profile ? $existing_profile->degree_path : null),
            'id_proof_front_path' => $upload_data['id_proof_front'] ?? ($existing_profile ? $existing_profile->id_proof_front_path : null),
            'id_proof_back_path' => $upload_data['id_proof_back'] ?? ($existing_profile ? $existing_profile->id_proof_back_path : null),
            'additional_certs_paths' => $upload_data['additional_certs'] ?? ($existing_profile ? $existing_profile->additional_certs_paths : null),
            'status' => 'pending',
            'submitted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => null
        ];

        log_message('debug', 'Data to save: ' . json_encode($data));

        // Insert or update profile
        try {
            $success = $existing_profile
                ? $this->Instructor_model->update_profile($user_id, $data)
                : $this->Instructor_model->insert_profile($data);

            if ($success) {
                log_message('debug', 'Profile ' . ($existing_profile ? 'updated' : 'saved') . ' successfully');
                $this->session->set_flashdata('success', 'Profile ' . ($existing_profile ? 'updated' : 'submitted') . ' successfully. Awaiting approval.');
                redirect('instructor/dashboard/pending');
            } else {
                throw new Exception('Database operation failed');
            }
        } catch (Exception $e) {
            $db_error = $this->db->error();
            log_message('error', 'Failed to ' . ($existing_profile ? 'update' : 'save') . ' profile. DB Error: ' . json_encode($db_error));
            $this->session->set_flashdata('errors', ['database' => 'Failed to save profile: ' . $db_error['message']]);
            $this->session->set_flashdata('active_tab', 'documents');
            redirect('instructor/profile');
        }
    }

    private function _get_validation_rules($is_new_submission) {
        $rules = [
            ['field' => 'name', 'label' => 'Full Name', 'rules' => 'required|trim|max_length[255]'],
            ['field' => 'gender', 'label' => 'Gender', 'rules' => 'required|in_list[Male,Female]'],
            ['field' => 'dob', 'label' => 'Date of Birth', 'rules' => 'required|callback_valid_date'],
            ['field' => 'phone_number', 'label' => 'Phone Number', 'rules' => 'required|regex_match[/\+?[0-9]{10,15}/]|max_length[20]'],
            ['field' => 'languages', 'label' => 'Languages', 'rules' => 'required|trim|max_length[100]'],
            ['field' => 'bio', 'label' => 'Bio', 'rules' => 'required|min_length[100]'],
            ['field' => 'education', 'label' => 'Education', 'rules' => 'required|trim|max_length[255]'],
            ['field' => 'video_intro', 'label' => 'Video Introduction', 'rules' => 'required|valid_url|max_length[255]'],
            ['field' => 'experience', 'label' => 'Experience', 'rules' => 'required'],
            ['field' => 'specialization', 'label' => 'Specialization', 'rules' => 'required|trim|max_length[255]'],
            ['field' => 'terms_agreement', 'label' => 'Terms Agreement', 'rules' => 'required']
        ];

        return $rules;
    }

    public function valid_date($date) {
        if (!$date) {
            $this->form_validation->set_message('valid_date', 'The Date of Birth field is required.');
            return false;
        }

        $dob = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dob || $dob->format('Y-m-d') !== $date) {
            $this->form_validation->set_message('valid_date', 'Please enter a valid date of birth.');
            return false;
        }

        $today = new DateTime();
        $age = $today->diff($dob)->y;
        if ($age < 18) {
            $this->form_validation->set_message('valid_date', 'You must be at least 18 years old.');
            return false;
        }

        if ($dob > $today) {
            $this->form_validation->set_message('valid_date', 'Date of birth cannot be in the future.');
            return false;
        }

        return true;
    }
}