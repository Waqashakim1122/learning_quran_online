<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Course_model', 'Instructor_model', 'Testimonial_model', 'User_model']);
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'email']);
    }

   public function index() {
    $data['title'] = 'Learn Quran Online - QuranTeach';
    $data['testimonials'] = $this->Testimonial_model->get_published_testimonials(2);
    $data['courses'] = $this->Course_model->get_published_courses(3);
    $data['instructors'] = $this->Instructor_model->get_approved_instructors(4, 0);
    $this->load->view('templates/header', $data);
    $this->load->view('templates/navbar');
    $this->load->view('home/index', $data);
    $this->load->view('templates/footer');
}

    public function course_detail($slug = NULL) {
        if (!$slug) {
            show_404();
        }
        $course = $this->Course_model->get_course_by_slug($slug);
        if (!$course || $course->status != 'published') {
            show_404();
        }
        $data['course'] = $course;
        $data['title'] = html_escape($course->course_name) . ' - QuranTeach';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('home/course_detail', $data);
        $this->load->view('templates/footer');
    }

    public function testimonials() {
        $this->load->library('pagination');
        $limit = 10;
        $offset = $this->uri->segment(3, 0);
        $data['testimonials'] = $this->Testimonial_model->get_published_testimonials_paginated($limit, $offset);
        $data['title'] = 'Testimonials - QuranTeach';
        $config = [
            'base_url' => base_url('home/testimonials'),
            'total_rows' => $this->Testimonial_model->get_total_testimonials(),
            'per_page' => $limit,
            'uri_segment' => 3
        ];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('home/testimonials', $data);
        $this->load->view('templates/footer');
    }

    public function all_courses() {
        $this->load->library('pagination');
        $limit = 9;
        $offset = $this->uri->segment(3, 0);
        $data['courses'] = $this->Course_model->get_published_courses_paginated($limit, $offset);
        $data['title'] = 'All Courses - QuranTeach';
        $config = [
            'base_url' => base_url('home/all_courses'),
            'total_rows' => $this->Course_model->get_total_published_courses(),
            'per_page' => $limit,
            'uri_segment' => 3
        ];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('home/all_courses', $data);
        $this->load->view('templates/footer');
    }

    public function all_instructors() {
        $instructors = $this->Instructor_model->get_approved_instructors();
        $data['instructors'] = array_map(function($instructor) {
            return (object)[
                'instructor_id' => $instructor->instructor_id,
                'name' => $instructor->name,
                'specialization' => $instructor->specialization,
                'experience' => $instructor->experience,
                'bio' => $instructor->bio,
                'image' => $instructor->image,
                'education' => property_exists($instructor, 'education') ? $instructor->education : 'No education details provided',
                'student_count' => $this->Instructor_model->count_students($instructor->instructor_id),
                'course_count' => $this->Instructor_model->count_instructor_courses($instructor->instructor_id)
            ];
        }, $instructors);
        $data['title'] = 'Our Instructors - QuranTeach';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('home/all_instructors', $data);
        $this->load->view('templates/footer');
    }

    public function about_us() {
        $data['title'] = 'About Us - QuranTeach';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('home/about_us', $data);
        $this->load->view('templates/footer');
    }

    public function contact_us() {
        $data['title'] = 'Contact Us - QuranTeach';
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('message', 'Message', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('home/contact_us', $data);
            $this->load->view('templates/footer');
        } else {
            $name = $this->input->post('name', TRUE);
            $email = $this->input->post('email', TRUE);
            $subject = $this->input->post('subject', TRUE);
            $message = $this->input->post('message', TRUE);
            $this->email->from($email, $name);
            $this->email->to('support@quranteach.com');
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                $this->session->set_flashdata('message', 'Your message has been sent successfully!');
            } else {
                $this->session->set_flashdata('message', 'Failed to send your message. Please try again.');
            }
            redirect('home/contact_us');
        }
    }

    public function submit_review() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please log in to submit a review.');
            redirect('auth/login');
        }
        $data['title'] = 'Submit Review - QuranTeach';
        $this->form_validation->set_rules('message', 'Review Message', 'required|trim');
        $this->form_validation->set_rules('rating', 'Rating', 'required|integer|greater_than[0]|less_than[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('home/testimonials', $data);
            $this->load->view('templates/footer');
        } else {
            $student_name = $this->session->userdata('username');
            if (empty($student_name)) {
                $user_id = $this->session->userdata('user_id');
                if ($user_id) {
                    $user = $this->User_model->get_user_by_id($user_id);
                    $student_name = $user->name ?? 'Anonymous';
                } else {
                    log_message('error', 'User ID not found in session for review submission.');
                    $student_name = 'Anonymous';
                }
            }
            if (empty($student_name)) {
                $student_name = 'Anonymous';
            }
            $review_data = [
                'student_name' => $student_name,
                'message' => $this->input->post('message', TRUE),
                'rating' => $this->input->post('rating', TRUE),
                'status' => 'published',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->Testimonial_model->insert_testimonial($review_data);
            $this->session->set_flashdata('success', 'Thank you for your review! It will be published after approval.');
            redirect('home/testimonials');
        }
    }
}
?>