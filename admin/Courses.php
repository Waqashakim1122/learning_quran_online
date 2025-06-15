<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Course_model', 'User_model']);
        
        if ($this->session->userdata('role') != 'admin') {
            redirect('login');
        }
    }

    public function index() {
        $data['courses'] = $this->Course_model->get_all_courses_with_instructors();
        $data['title'] = 'Manage Courses';
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/courses/index', $data);
        $this->load->view('admin/templates/footer');
    }

    public function create() {
        $this->form_validation->set_rules([
            ['field' => 'course_name', 'label' => 'Course Name', 'rules' => 'required|max_length[100]'],
            ['field' => 'description', 'label' => 'Description', 'rules' => 'required'],
            ['field' => 'category', 'label' => 'Category', 'rules' => 'required|in_list[Noorani Qaida,Hifz,Tajweed,Tafseer,Translation]'],
            ['field' => 'level', 'label' => 'Level', 'rules' => 'required|in_list[beginner,intermediate,advanced]'],
            ['field' => 'featured_image', 'label' => 'Featured Image', 'rules' => 'callback_validate_image']
        ]);

        if ($this->form_validation->run()) {
            $featured_image = '';
            
            if (!empty($_FILES['featured_image']['name'])) {
                $upload_result = $this->Course_model->upload_featured_image('featured_image');
                
                if (isset($upload_result['error'])) {
                    $this->session->set_flashdata('error', $upload_result['error']);
                    redirect('admin/courses/create');
                } else {
                    $featured_image = $upload_result['success']['file_name'];
                }
            }

            // Generate unique slug
            $base_slug = url_title($this->input->post('course_name'), 'dash', true);
            $slug = $base_slug;
            $counter = 1;
            
            while ($this->Course_model->slug_exists($slug)) {
                $slug = $base_slug . '-' . $counter;
                $counter++;
                if ($counter > 100) {
                    $this->session->set_flashdata('error', 'Unable to generate a unique slug. Please try a different course name.');
                    redirect('admin/courses/create');
                }
            }

            $course_data = [
                'course_name'   => $this->input->post('course_name'),
                'slug'          => $slug,
                'description'   => $this->input->post('description'),
                'category'      => $this->input->post('category'),
                'level'         => $this->input->post('level'),
                'status'        => $this->input->post('status'),
                'created_by'    => $this->session->userdata('user_id'),
                'duration'      => $this->input->post('duration') ?? '',
                'price'         => $this->input->post('price') ?? 0.00,
                'featured_image' => $featured_image,
                'is_featured'   => $this->input->post('is_featured') ? 1 : 0,
                'approval_status' => $this->input->post('approval_status'),
                'is_active'     => $this->input->post('is_active') ? 1 : 0,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];

            if ($this->Course_model->create($course_data)) {
                $this->session->set_flashdata('success', 'Course created successfully!');
                redirect('admin/courses');
            } else {
                $this->session->set_flashdata('error', 'Failed to create course.');
                redirect('admin/courses/create');
            }
        }

        $data = [
            'title' => 'Create New Course',
            'categories' => $this->Course_model->get_categories()
        ];
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/courses/create', $data);
        $this->load->view('admin/templates/footer');
    }

    public function edit($course_id) {
        $data['course'] = $this->Course_model->get_course_by_id($course_id);
        
        if (!$data['course']) {
            $this->session->set_flashdata('error', 'Course not found');
            redirect('admin/courses');
        }
        
        $this->form_validation->set_rules([
            ['field' => 'course_name', 'label' => 'Course Name', 'rules' => 'required|max_length[100]'],
            ['field' => 'description', 'label' => 'Description', 'rules' => 'required'],
            ['field' => 'category', 'label' => 'Category', 'rules' => 'required|in_list[Noorani Qaida,Hifz,Tajweed,Tafseer,Translation]'],
            ['field' => 'level', 'label' => 'Level', 'rules' => 'required|in_list[beginner,intermediate,advanced]'],
            ['field' => 'featured_image', 'label' => 'Featured Image', 'rules' => 'callback_validate_image']
        ]);
        
        if ($this->form_validation->run()) {
            // Generate unique slug
            $base_slug = url_title($this->input->post('course_name'), 'dash', true);
            $slug = $base_slug;
            $counter = 1;
            
            while ($this->Course_model->slug_exists($slug, $course_id)) {
                $slug = $base_slug . '-' . $counter;
                $counter++;
                if ($counter > 100) {
                    $this->session->set_flashdata('error', 'Unable to generate a unique slug. Please try a different course name.');
                    redirect('admin/courses/edit/' . $course_id);
                }
            }

            $course_data = [
                'course_name' => $this->input->post('course_name'),
                'slug' => $slug,
                'description' => $this->input->post('description'),
                'category' => $this->input->post('category'),
                'level' => $this->input->post('level'),
                'status' => $this->input->post('status'),
                'duration' => $this->input->post('duration') ?? '',
                'price' => $this->input->post('price') ?? 0.00,
                'is_featured' => $this->input->post('is_featured') ? 1 : 0,
                'approval_status' => $this->input->post('approval_status'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if (!empty($_FILES['featured_image']['name'])) {
                $upload_result = $this->Course_model->upload_featured_image('featured_image');
                
                if (isset($upload_result['error'])) {
                    $this->session->set_flashdata('error', $upload_result['error']);
                    redirect('admin/courses/edit/' . $course_id);
                } else {
                    if (!empty($data['course']->featured_image)) {
                        $old_image_path = './Uploads/courses/' . $data['course']->featured_image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                    $course_data['featured_image'] = $upload_result['success']['file_name'];
                }
            }
            
            if ($this->Course_model->update($course_id, $course_data)) {
                $this->session->set_flashdata('success', 'Course updated successfully!');
                redirect('admin/courses');
            } else {
                $this->session->set_flashdata('error', 'Failed to update course.');
            }
        }
        
        $data['title'] = 'Edit Course: ' . htmlspecialchars($data['course']->course_name);
        $data['categories'] = $this->Course_model->get_categories();
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/courses/edit', $data);
        $this->load->view('admin/templates/footer');
    }
     public function view($course_id) {
        $data['course'] = $this->Course_model->get_course_by_id($course_id);
        
        if (!$data['course']) {
            $this->session->set_flashdata('error', 'Course not found');
            redirect('admin/courses');
        }
        
        $data['title'] = 'View Course: ' . htmlspecialchars($data['course']->course_name);
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/courses/view', $data);
        $this->load->view('admin/templates/footer');
    }

    public function validate_image() {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && empty($_FILES['featured_image']['name'])) {
            // For create, image is required; for edit, it's optional
            if ($this->uri->segment(3) == 'create') {
                $this->form_validation->set_message('validate_image', 'The Featured Image field is required.');
                return false;
            }
        }
        return true;
    }
public function delete($course_id) {
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        if ($this->Course_model->delete($course_id)) {
            $this->session->set_flashdata('success', 'Course and related data deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete course. Please check for related data or try again.');
            log_message('error', 'Failed to delete course ID ' . $course_id . ': ' . $this->db->error()['message']);
        }
    } else {
        $this->session->set_flashdata('error', 'Invalid request method.');
    }
    redirect('admin/courses');
}

    public function toggle_status($course_id) {
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found');
            redirect('admin/courses');
        }
        
        $new_status = ($course->is_active == 1) ? 0 : 1;
        
        if ($this->Course_model->update($course_id, ['is_active' => $new_status])) {
            $status_text = ($new_status == 1) ? 'activated' : 'deactivated';
            $this->session->set_flashdata('success', 'Course ' . $status_text . ' successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update course status.');
        }
        
        redirect('admin/courses');
    }

    public function toggle_featured($course_id) {
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found');
            redirect('admin/courses');
        }
        
        $new_status = ($course->is_featured == 1) ? 0 : 1;
        
        if ($this->Course_model->update($course_id, ['is_featured' => $new_status])) {
            $status_text = ($new_status == 1) ? 'added to' : 'removed from';
            $this->session->set_flashdata('success', 'Course ' . $status_text . ' featured courses successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update featured status.');
        }
        
        redirect('admin/courses');
    }

    public function manage_enrollments($course_id) {
        $data['course'] = $this->Course_model->get_course_by_id($course_id);
        if (!$data['course']) {
            $this->session->set_flashdata('error', 'Course not found');
            redirect('admin/courses');
        }
        $data['enrollments'] = $this->Course_model->get_enrollments_by_course($course_id);
        $data['instructors'] = $this->Course_model->get_available_instructors();
        $data['title'] = 'Manage Enrollments: ' . htmlspecialchars($data['course']->course_name);
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/courses/manage_enrollments', $data);
        $this->load->view('admin/templates/footer');
    }

    public function assign_instructor($enrollment_id) {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $instructor_id = $this->input->post('instructor_id');
            if ($this->Course_model->assign_instructor($enrollment_id, $instructor_id)) {
                $this->session->set_flashdata('success', 'Instructor assigned successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to assign instructor.');
            }
            $enrollment = $this->db->get_where('enrollments', ['enrollment_id' => $enrollment_id])->row();
            redirect('admin/courses/manage_enrollments/' . $enrollment->course_id);
        }
    }
}
?>