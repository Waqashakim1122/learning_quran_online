
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_active() {
        $this->db->where('status', 'published');
        $this->db->where('is_active', 1);
        return $this->db->get('courses')->result();
    }

    public function count_active_courses() {
        $this->db->where('status', 'published');
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('courses');
    }

    public function get_available_courses($level = null) {
        $this->db->where('status', 'published');
        $this->db->where('is_active', 1);
        $this->db->where('is_available', 1);
        if ($level) {
            $this->db->where('level', $level);
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('courses')->result();
    }

    public function get_course($course_id) {
        $this->db->select('course_id, course_name, category, status, description, level, duration, price, featured_image, is_active, is_available');
        $this->db->where('course_id', $course_id);
        $this->db->where('status', 'published');
        $this->db->where('is_active', 1);
        $query = $this->db->get('courses');
        return $query->num_rows() > 0 ? $query->row() : false;
    }

    public function get_recent_courses($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('courses')->result();
    }

    public function get_top_performing($limit = 3) {
        $this->db->select('c.*, COUNT(e.enrollment_id) as student_count, AVG(e.progress) as completion_rate');
        $this->db->from('courses c');
        $this->db->join('enrollments e', 'e.course_id = c.course_id', 'left');
        $this->db->group_by('c.course_id');
        $this->db->order_by('student_count', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_course_by_id($course_id) {
        $this->db->select('course_id, course_name, slug, description, category, level, status, created_by, duration, price, featured_image, is_featured, approval_status, is_active, created_at, updated_at');
        $this->db->from('courses');
        $this->db->where('course_id', $course_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_course_modules($course_id) {
        // Note: Returns empty as course_modules table doesn't exist
        $this->db->where('course_id', $course_id);
        $this->db->order_by('module_order', 'asc');
        return $this->db->get('course_modules')->result();
    }

    public function create($data) {
        return $this->db->insert('courses', $data);
    }

    public function update($course_id, $data) {
        $this->db->where('course_id', $course_id);
        return $this->db->update('courses', $data);
    }

    public function delete($course_id) {
        $this->db->trans_start();

        // Get the course to delete its image
        $course = $this->get_course_by_id($course_id);
        if ($course && !empty($course->featured_image)) {
            $image_path = './Uploads/courses/' . $course->featured_image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Get all enrollments for this course
        $enrollments = $this->db->get_where('enrollments', ['course_id' => $course_id])->result();
        
        foreach ($enrollments as $enrollment) {
            // Get conversations for this enrollment
            $conversations = $this->db->get_where('conversations', ['enrollment_id' => $enrollment->enrollment_id])->result();
            
            foreach ($conversations as $conversation) {
                // Delete messages related to this conversation
                $this->db->where('conversation_id', $conversation->conversation_id);
                $this->db->delete('messages');
            }
            
            // Delete conversations related to this enrollment
            $this->db->where('enrollment_id', $enrollment->enrollment_id);
            $this->db->delete('conversations');
            
            // Delete the enrollment
            $this->db->where('enrollment_id', $enrollment->enrollment_id);
            $this->db->delete('enrollments');
        }

        // Delete live_sessions to handle foreign key constraint
        $this->db->where('course_id', $course_id);
        $this->db->delete('live_sessions');

        // Delete the course
        $this->db->where('course_id', $course_id);
        $result = $this->db->delete('courses');

        $this->db->trans_complete();

        return $this->db->trans_status() === FALSE ? FALSE : $result;
    }

    public function get_all_courses() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('courses')->result();
    }

    public function get_total_courses() {
        return $this->db->count_all('courses');
    }

    public function get_courses_this_month() {
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        return $this->db->count_all_results('courses');
    }

    public function get_featured_courses($limit = 4) {
        $this->db->select('course_id, course_name AS title, description, featured_image AS image, slug');
        $this->db->where('is_featured', 1);
        $this->db->where('status', 'published');
        $this->db->limit($limit);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('courses')->result();
    }

    public function get_published_courses($limit = 0) {
    $this->db->select('course_id, course_name, description, featured_image, slug, category, level, price, status, is_active, is_available');
    $this->db->from('courses');
    $this->db->where('status', 'published');
    $this->db->where('is_active', 1);
    $this->db->where('is_available', 1);
    if ($limit > 0) {
        $this->db->limit($limit);
    }
    $query = $this->db->get();
    return $query->result();
}

    public function get_published_courses_paginated($limit, $offset) {
        $this->db->select('courses.course_name, courses.description, courses.featured_image, courses.slug, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'users.id = courses.instructor_id', 'left');
        $this->db->where('courses.status', 'published');
        $this->db->where('courses.is_active', 1);
        $this->db->where('courses.is_featured', 1);
        $this->db->order_by('courses.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_total_published_courses() {
        $this->db->from('courses');
        $this->db->where('status', 'published');
        $this->db->where('is_active', 1);
        $this->db->where('is_featured', 1);
        return $this->db->count_all_results();
    }

    public function get_all_featured_courses() {
        $this->db->select('courses.course_name, courses.description, courses.featured_image, courses.slug, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'users.id = courses.instructor_id', 'left');
        $this->db->where('courses.status', 'published');
        $this->db->where('courses.is_active', 1);
        $this->db->where('courses.is_featured', 1);
        $this->db->order_by('courses.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_course_by_slug($slug) {
        $this->db->where('slug', $slug);
        $this->db->where('status', 'published');
        return $this->db->get('courses')->row();
    }

    public function get_categories() {
        return ['Noorani Qaida', 'Hifz', 'Tajweed', 'Tafseer', 'Translation'];
    }

    public function get_course_enrollments($course_id) {
        $this->db->select('e.enrollment_id, u.name as student_name, u.email as student_email, i.name as instructor_name, e.enrollment_date, e.status');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->join('users i', 'i.id = e.assigned_instructor_id', 'left');
        $this->db->where('e.course_id', $course_id);
        return $this->db->get()->result();
    }

    public function get_course_with_details($course_id) {
        $this->db->select('courses.*, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'courses.created_by = users.id', 'left');
        $this->db->where('courses.course_id', $course_id);
        $query = $this->db->get();
        $course = $query->row();
        if ($course) {
            $course->syllabus = []; // Empty syllabus as course_modules doesn't exist
        }
        return $course;
    }

    public function get_instructor_courses($instructor_id) {
        $this->db->select('c.course_id, c.course_name');
        $this->db->from('courses c');
        $this->db->join('enrollments e', 'e.course_id = c.course_id');
        $this->db->where('e.assigned_instructor_id', $instructor_id);
        $this->db->group_by('c.course_id');
        return $this->db->get()->result();
    }

    public function get_all_courses_with_instructors() {
        $this->db->select('courses.*, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'courses.created_by = users.id', 'left');
        $this->db->where('users.role', 'admin');
        $this->db->order_by('courses.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_available_instructors() {
        $this->db->select('users.id, users.name, instructor_profiles.specialization, instructor_profiles.languages');
        $this->db->from('users');
        $this->db->join('instructor_profiles', 'users.id = instructor_profiles.user_id', 'inner');
        $this->db->where('users.role', 'instructor');
        $this->db->where('users.is_approved', 1);
        $this->db->where('instructor_profiles.status', 'approved');
        $query = $this->db->get();
        return $query->result();
    }

    public function enroll_student($course_id, $student_id, $preferred_instructor_id = null, $data = []) {
        $enrollment_data = array_merge([
            'course_id' => $course_id,
            'student_id' => $student_id,
            'preferred_instructor_id' => $preferred_instructor_id,
            'status' => 'pending_approval',
            'enrollment_date' => date('Y-m-d'),
            'enrolled_at' => date('Y-m-d H:i:s'),
            'progress' => 0,
            'admin_assign_instructor' => 1
        ], $data);
        return $this->db->insert('enrollments', $enrollment_data);
    }

    public function assign_instructor($enrollment_id, $instructor_id) {
        $data = [
            'assigned_instructor_id' => $instructor_id,
            'status' => 'approved',
            'admin_assign_instructor' => 0,
            'last_accessed' => date('Y-m-d H:i:s')
        ];
        $this->db->where('enrollment_id', $enrollment_id);
        return $this->db->update('enrollments', $data);
    }

    public function deactivate_instructor_courses($instructor_id) {
        $this->db->trans_start();

        $this->db->where('instructor_id', $instructor_id);
        $this->db->update('courses', [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Failed to deactivate courses for instructor ID: ' . $instructor_id . ' | DB Error: ' . print_r($this->db->error(), TRUE));
            return FALSE;
        }

        log_message('debug', 'Courses deactivated for instructor ID: ' . $instructor_id);
        return TRUE;
    }

    public function activate_instructor_courses($instructor_id) {
        $this->db->trans_start();

        $this->db->where('instructor_id', $instructor_id);
        $this->db->update('courses', [
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Failed to activate courses for instructor ID: ' . $instructor_id . ' | DB Error: ' . print_r($this->db->error(), TRUE));
            return FALSE;
        }

        log_message('debug', 'Courses activated for instructor ID: ' . $instructor_id);
        return TRUE;
    }

    public function update_enrollment_status($enrollment_id, $status, $instructor_id = null) {
        $data = [
            'status' => $status,
            'last_accessed' => date('Y-m-d H:i:s')
        ];
        if ($status == 'approved' && $instructor_id) {
            $data['assigned_instructor_id'] = $instructor_id;
            $data['admin_assign_instructor'] = 0;
        }
        $this->db->where('enrollment_id', $enrollment_id);
        return $this->db->update('enrollments', $data);
    }

    public function get_enrollments_by_course($course_id) {
        $this->db->select('enrollments.*, u1.name as student_name, u2.name as preferred_instructor_name, u3.name as assigned_instructor_name');
        $this->db->from('enrollments');
        $this->db->join('users u1', 'enrollments.student_id = u1.id', 'left');
        $this->db->join('users u2', 'enrollments.preferred_instructor_id = u2.id', 'left');
        $this->db->join('users u3', 'enrollments.assigned_instructor_id = u3.id', 'left');
        $this->db->where('enrollments.course_id', $course_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_pending_enrollments_by_instructor($instructor_id) {
        $this->db->select('enrollments.*, courses.course_name, u1.name as student_name');
        $this->db->from('enrollments');
        $this->db->join('courses', 'enrollments.course_id = courses.course_id');
        $this->db->join('users u1', 'enrollments.student_id = u1.id');
        $this->db->where('enrollments.preferred_instructor_id', $instructor_id);
        $this->db->where('enrollments.status', 'pending_approval');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function upload_featured_image($file_input_name) {
        $config['upload_path'] = './Uploads/courses/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = true;
        
        // Ensure upload directory exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload($file_input_name)) {
            return ['error' => strip_tags($this->upload->display_errors())];
        } else {
            $upload_data = $this->upload->data();
            
            // Validate MIME type
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $upload_data['full_path']);
            finfo_close($finfo);
            
            if (!in_array($mime, $allowed_mimes)) {
                unlink($upload_data['full_path']); // Delete invalid file
                return ['error' => 'Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.'];
            }

            // Check if GD is available
            if (extension_loaded('gd') && function_exists('gd_info')) {
                // Resize image
                $this->load->library('image_lib');
                $resize_config = [
                    'image_library' => 'gd2',
                    'source_image' => $upload_data['full_path'],
                    'maintain_ratio' => true,
                    'width' => 800,
                    'height' => 600,
                    'new_image' => $upload_data['full_path'], // Overwrite original
                ];
                
                $this->image_lib->clear();
                $this->image_lib->initialize($resize_config);
                
                if (!$this->image_lib->resize()) {
                    $error = strip_tags($this->image_lib->display_errors());
                    unlink($upload_data['full_path']); // Delete file on resize failure
                    return ['error' => 'Image resizing failed: ' . $error];
                }
                
                $this->image_lib->clear();
            } else {
                // GD not available; skip resizing but proceed with upload
                log_message('error', 'GD library is not available. Skipping image resizing.');
            }
            
            return ['success' => $upload_data];
        }
    }

    public function slug_exists($slug) {
        $this->db->where('slug', $slug);
        $query = $this->db->get('courses');
        return $query->num_rows() > 0;
    }
}
?>