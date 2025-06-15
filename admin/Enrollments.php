<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollments extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Please log in to access the admin panel.');
            redirect('login');
        }
        $this->load->model('Enrollment_model');
        $this->load->model('Course_model');
        $this->load->model('Instructor_model');
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function pending() {
        $data['title'] = 'All Enrollment Requests';
        $search = $this->input->get('search', TRUE);
        $course_id = $this->input->get('course_id', TRUE);
        $status = $this->input->get('status', TRUE);
        $data['pending_enrollments'] = $this->Enrollment_model->get_pending_enrollments_with_instructors($search, $course_id, $status);
        $data['courses'] = $this->Course_model->get_all_active();
        $data['search'] = $search;
        $data['course_id'] = $course_id;
        $data['status'] = $status;
         
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/enrollments/pending_enrollments', $data);
        $this->load->view('admin/templates/footer');
    }

    public function active() {
        $data['title'] = 'Active Enrollments';
        $search = $this->input->get('search', TRUE);
        $course_id = $this->input->get('course_id', TRUE);
        $data['active_enrollments'] = $this->Enrollment_model->get_active_enrollments($search, $course_id);
        $data['courses'] = $this->Course_model->get_all_active();
        $data['search'] = $search;
        $data['course_id'] = $course_id;
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/enrollments/active_enrollments', $data);
        $this->load->view('admin/templates/footer');
    }

    public function assign($enrollment_id) {
        $data['enrollment'] = $this->Enrollment_model->get_enrollment_with_preferred_instructor($enrollment_id);
        if (!$data['enrollment']) {
            $this->session->set_flashdata('error', 'Enrollment not found.');
            redirect('admin/enrollments/pending');
        }
        $data['instructors'] = $this->Instructor_model->get_by_course($data['enrollment']->course_id);
        log_message('debug', 'Instructors for Course ID ' . $data['enrollment']->course_id . ': ' . json_encode($data['instructors']));
        $data['title'] = 'Assign Instructor';

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/enrollments/assign', $data);
    }

    public function process_assignment() {
        $this->form_validation->set_rules('enrollment_id', 'Enrollment ID', 'required|numeric');
        $this->form_validation->set_rules('instructor_id', 'Instructor', 'required|numeric');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/enrollments/pending');
        }

        $enrollment_id = $this->input->post('enrollment_id');
        $instructor_id = $this->input->post('instructor_id');
        $admin_id = $this->session->userdata('user_id');

        if ($this->Enrollment_model->update_enrollment($enrollment_id, ['assigned_instructor_id' => $instructor_id])) {
            log_message('info', "Admin ID {$admin_id} assigned Instructor ID {$instructor_id} to Enrollment ID {$enrollment_id}");
            $this->session->set_flashdata('success', 'Instructor assigned successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to assign instructor.');
        }
        redirect('admin/enrollments/pending');
    }

   public function enrollment_details($enrollment_id = null) {
    if (!$enrollment_id || !is_numeric($enrollment_id)) {
        log_message('error', 'Invalid Enrollment ID accessed: ' . $enrollment_id);
        $this->session->set_flashdata('error', 'Invalid Enrollment ID.');
        redirect('admin/enrollments/pending');
    }
    $data['enrollment_details'] = $this->Enrollment_model->get_enrollment_details($enrollment_id);
    if (!$data['enrollment_details']) {
        $this->session->set_flashdata('error', 'Enrollment details not found.');
        redirect('admin/enrollments/pending');
    }
    $data['title'] = 'Enrollment Details';
    $this->load->view('admin/templates/header', $data);
    $this->load->view('admin/enrollments/enrollment_details', $data); // Line 96
    $this->load->view('admin/templates/footer');
}

    public function approve_enrollment($enrollment_id) {
        $enrollment = $this->Enrollment_model->get_enrollment($enrollment_id);
        if (!$enrollment) {
            $this->session->set_flashdata('error', 'Enrollment not found.');
            redirect('admin/enrollments/pending');
        }
        if (empty($enrollment->assigned_instructor_id)) {
            $this->session->set_flashdata('error', 'Please assign an instructor before approving.');
            redirect('admin/enrollments/pending');
        }
        $admin_id = $this->session->userdata('user_id');
        if ($this->Enrollment_model->update_enrollment($enrollment_id, ['status' => 'active'])) {
            log_message('info', "Admin ID {$admin_id} approved Enrollment ID {$enrollment_id}");
            $this->session->set_flashdata('success', 'Enrollment approved successfully.');
            redirect('admin/enrollments/active');
        } else {
            $this->session->set_flashdata('error', 'Error approving enrollment.');
            redirect('admin/enrollments/pending');
        }
    }

    public function reject_enrollment($enrollment_id) {
        $admin_id = $this->session->userdata('user_id');
        $enrollment = $this->Enrollment_model->get_enrollment($enrollment_id);
        if (!$enrollment) {
            $this->session->set_flashdata('error', 'Enrollment not found.');
            redirect('admin/enrollments/pending');
        }
        if ($this->Enrollment_model->update_enrollment($enrollment_id, ['status' => 'rejected'])) {
            log_message('info', "Admin ID {$admin_id} rejected Enrollment ID {$enrollment_id}");
            $this->session->set_flashdata('success', 'Enrollment rejected successfully.');
        } else {
            $error = $this->db->error();
            log_message('error', "Failed to reject Enrollment ID {$enrollment_id}: " . json_encode($error));
            $this->session->set_flashdata('error', 'Error rejecting enrollment. Check logs for details.');
        }
        redirect('admin/enrollments/pending');
    }

    public function update_status($enrollment_id) {
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[pending_approval,active,completed,dropped,rejected]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/enrollments/enrollment_details/' . $enrollment_id);
        }
        $status = $this->input->post('status');
        $admin_id = $this->session->userdata('user_id');
        if ($this->Enrollment_model->update_enrollment($enrollment_id, ['status' => $status])) {
            log_message('info', "Admin ID {$admin_id} updated Enrollment ID {$enrollment_id} to status {$status}");
            $this->session->set_flashdata('success', 'Enrollment status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update enrollment status.');
        }
        redirect('admin/enrollments/enrollment_details/' . $enrollment_id);
    }

    public function all() {
        $data['title'] = 'All Enrollments';
        $search = $this->input->get('search', TRUE);
        $course_id = $this->input->get('course_id', TRUE);
        $data['all_enrollments'] = $this->Enrollment_model->get_all_enrollments($search, $course_id);
        $data['courses'] = $this->Course_model->get_all_active();
        $data['search'] = $search;
        $data['course_id'] = $course_id;
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/enrollments/all_enrollments', $data);
        $this->load->view('admin/templates/footer');
    }

    public function pending_assignments() {
        $data['title'] = 'Pending Instructor Assignments';
        $data['enrollments'] = $this->Enrollment_model->get_pending_assignments();
        $data['courses'] = $this->Course_model->get_all_active();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/enrollments/pending', $data);
        $this->load->view('admin/templates/footer');
    }

    public function approve($enrollment_id) {
        $enrollment = $this->Enrollment_model->get_enrollment($enrollment_id);

        if (!$enrollment) {
            $this->session->set_flashdata('error', 'Enrollment not found.');
            redirect('admin/enrollments');
            return;
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('instructor_id', 'Instructor', 'required|integer');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $instructor_id = $this->input->post('instructor_id');
                log_message('debug', 'Attempting to assign instructor_id: ' . $instructor_id . ' to enrollment_id: ' . $enrollment_id);

                $data = [
                    'assigned_instructor_id' => $instructor_id,
                    'status' => 'approved',
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($this->Enrollment_model->update_enrollment($enrollment_id, $data)) {
                    log_message('debug', 'Successfully updated enrollment_id: ' . $enrollment_id . ' with assigned_instructor_id: ' . $instructor_id);
                    $this->session->set_flashdata('success', 'Enrollment approved and instructor assigned successfully.');
                } else {
                    log_message('error', 'Failed to update enrollment_id: ' . $enrollment_id);
                    $this->session->set_flashdata('error', 'Failed to approve enrollment.');
                }
                redirect('admin/enrollments');
                return;
            }
        }

        $data = [
            'title' => 'Approve Enrollment',
            'enrollment' => $enrollment,
            'instructors' => $this->Instructor_model->get_by_course($enrollment->course_id) // Changed to Instructor_model
        ];

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/enrollments/approve', $data);
        $this->load->view('admin/templates/footer');
    }
}