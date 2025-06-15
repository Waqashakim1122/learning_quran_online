<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instructor_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_profile($data) {
        if (!$this->db->insert('instructor_profiles', $data)) {
            log_message('error', 'DB Error: ' . print_r($this->db->error(), TRUE));
            return FALSE;
        }
        return TRUE;
    }

    public function update_profile($user_id, $data) {
        $this->db->where('user_id', $user_id);
        if (!$this->db->update('instructor_profiles', $data)) {
            log_message('error', 'DB Error: ' . print_r($this->db->error(), TRUE));
            return FALSE;
        }
        return TRUE;
    }

    public function get_profile($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->get('instructor_profiles')->row();
    }

    public function profile_exists($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('instructor_profiles') > 0;
    }

    public function get_all_instructors() {
        $this->db->select('ip.id as instructor_id, ip.user_id, ip.name, ip.specialization, ip.experience, ip.bio, ip.profile_picture_path as image');
        $this->db->from('instructor_profiles ip');
        $this->db->join('users u', 'u.id = ip.user_id');
        $this->db->where('u.is_active', 1);
        $this->db->where('ip.status', 'approved');
        $this->db->order_by('ip.name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_instructors() {
        return $this->db->select('ip.id as instructor_id, ip.user_id, ip.name, ip.specialization, ip.experience, ip.bio, ip.profile_picture_path as image')
            ->from('instructor_profiles ip')
            ->join('users u', 'u.id = ip.user_id')
            ->where('ip.status', 'approved')
            ->get()
            ->result();
    }

    public function count_approved_instructors() {
        $this->db->where('instructor_profiles.status', 'approved');
        $this->db->where('users.is_active !=', 0);
        $this->db->from('instructor_profiles');
        $this->db->join('users', 'users.id = instructor_profiles.user_id');
        return $this->db->count_all_results();
    }

    public function calculate_instructor_growth() {
        $current_month = $this->db->query("SELECT COUNT(*) as count FROM instructor_profiles WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) AND status = 'approved'")->row()->count;
        $previous_month = $this->db->query("SELECT COUNT(*) as count FROM instructor_profiles WHERE MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND status = 'approved'")->row()->count;
        
        if ($previous_month == 0) return 0;
        return round(($current_month - $previous_month) / $previous_month * 100, 1);
    }

    public function get_recent_instructors($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('instructor_profiles')->result();
    }

    public function get_top_instructors($limit = 3) {
        $this->db->select('ip.*, COUNT(e.enrollment_id) as student_count, AVG(r.rating) as average_rating');
        $this->db->from('instructor_profiles ip');
        $this->db->join('enrollments e', 'e.instructor_id = ip.user_id', 'left'); // Use instructor_id
        $this->db->join('ratings r', 'r.instructor_id = ip.user_id', 'left');
        $this->db->group_by('ip.id');
        $this->db->order_by('student_count', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function count_students($instructor_id) {
        $this->db->select('COUNT(DISTINCT enrollments.student_id) as total');
        $this->db->from('enrollments');
        $this->db->join('courses', 'courses.course_id = enrollments.course_id');
        $this->db->where('enrollments.instructor_id', $instructor_id); // Use instructor_id
        $this->db->where('courses.is_active', 1);
        $this->db->where('courses.approval_status', 'approved');
        return $this->db->get()->row()->total;
    }
public function get_instructor_courses($instructor_id) {
        $this->db->select('c.course_id, c.course_name, c.category, c.status, COUNT(DISTINCT e.enrollment_id) as student_count');
        $this->db->from('courses c');
        $this->db->join('enrollments e', 'e.course_id = c.course_id', 'left');
        $this->db->where('e.assigned_instructor_id', $instructor_id);
        $this->db->where_in('e.status', ['active', 'approved']);
        $this->db->where('c.is_active', 1);
        $this->db->where('c.status', 'published');
        $this->db->group_by('c.course_id');
        $this->db->order_by('c.course_name', 'ASC');
        
        $query = $this->db->get();
        log_message('debug', 'Get Instructor Courses Query: ' . $this->db->last_query());
        log_message('debug', 'Instructor ID: ' . $instructor_id . ', Courses Found: ' . $query->num_rows());
        
        return $query->result();
    }

    public function get_assigned_students($instructor_id) {
        $this->db->select('u.id as student_id, u.name as student_name, u.email as student_email, c.course_name, c.course_id, e.enrolled_at as enrollment_date, e.enrollment_id, e.status as enrollment_status');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->where('e.assigned_instructor_id', $instructor_id);
        $this->db->where_in('e.status', ['active', 'approved']);
        $this->db->where('c.is_active', 1);
        $this->db->where('c.status', 'published');
        $this->db->where('u.role', 'student');
        $this->db->order_by('e.enrolled_at', 'DESC');
        
        $query = $this->db->get();
        
        log_message('debug', 'Get Assigned Students Query: ' . $this->db->last_query());
        log_message('debug', 'Instructor ID: ' . $instructor_id . ', Students Found: ' . $query->num_rows());
        
        return $query->result();
    }

    public function is_instructor_assigned($course_id, $instructor_id) {
        $this->db->where('course_id', $course_id);
        $this->db->where('instructor_id', $instructor_id); // Use instructor_id
        return $this->db->get('enrollments')->num_rows() > 0;
    }

    public function get_course_students($course_id) {
        $this->db->select('u.id as student_id, u.name as student_name, u.email as student_email, 
                            e.enrolled_at, e.progress, e.enrollment_id, e.status as enrollment_status');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->where('e.course_id', $course_id);
        // Removed e.status = 'active' to include pending enrollments
        return $this->db->get()->result();
    }

    public function get_course_details($course_id) {
        $this->db->where('course_id', $course_id);
        return $this->db->get('courses')->row();
    }

    public function get_instructor_availability($instructor_id) {
        $this->db->where('instructor_id', $instructor_id);
        $this->db->order_by('day_of_week, start_time');
        return $this->db->get('instructor_availability')->result();
    }

    public function get_instructor_reviews($instructor_id, $limit = 5) {
        $this->db->select('ratings.*, users.name as student_name');
        $this->db->from('ratings');
        $this->db->join('users', 'users.id = ratings.student_id');
        $this->db->where('ratings.instructor_id', $instructor_id);
        $this->db->order_by('ratings.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_recent_students($instructor_id, $limit = 3) {
        $this->db->select('users.id, users.name, users.email, MAX(enrollments.enrolled_at) as joined_at');
        $this->db->from('users');
        $this->db->join('enrollments', 'enrollments.student_id = users.id');
        $this->db->join('courses', 'courses.course_id = enrollments.course_id');
        $this->db->where('enrollments.instructor_id', $instructor_id); // Use instructor_id
        $this->db->where('users.role', 'student');
        $this->db->where('courses.is_active', 1);
        $this->db->where('courses.approval_status', 'approved');
        $this->db->group_by('users.id');
        $this->db->order_by('joined_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_average_rating($instructor_id) {
        $this->db->select_avg('rating');
        $this->db->where('instructor_id', $instructor_id);
        $result = $this->db->get('ratings')->row();
        return $result->rating ? (float)$result->rating : null;
    }

    public function count_instructor_courses($instructor_id) {
        $this->db->where('instructor_id', $instructor_id);
        $this->db->where('is_active', 1);
        $this->db->where('approval_status', 'approved');
        return $this->db->count_all_results('courses');
    }

    public function get_by_course($course_id) {
        $this->db->select('u.id AS instructor_id, u.name, ip.specialization');
        $this->db->from('users u');
        $this->db->join('instructor_profiles ip', 'ip.user_id = u.id');
        $this->db->where('u.role', 'instructor');
        $this->db->where('ip.status', 'approved');
        $this->db->where('u.is_active', 1);
        $this->db->join('enrollments e', 'e.instructor_id = u.id AND e.course_id = ' . (int)$course_id, 'left'); // Use instructor_id
        $this->db->order_by('u.name', 'ASC');
        $query = $this->db->get();
        log_message('debug', 'Get By Course Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function get_approved_instructors($limit = 10, $offset = 0) {
        $this->db->select('ip.id as instructor_id, ip.user_id, ip.name, ip.specialization, ip.experience, ip.bio, ip.profile_picture_path as image, ip.education, u.email, ip.submitted_at, u.is_active');
        $this->db->from('instructor_profiles ip');
        $this->db->join('users u', 'u.id = ip.user_id');
        $this->db->where('ip.status', 'approved');
        $this->db->where('u.is_active !=', 0);
        $this->db->order_by('ip.name', 'ASC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $result = $query->result();
        return $result ?: [];
    }

    public function get_suspended_instructors($limit = 10, $offset = 0) {
        $this->db->select('ip.id as instructor_id, ip.user_id, ip.name, ip.specialization, ip.experience, ip.bio, ip.profile_picture_path as image, u.email, ip.submitted_at, u.is_active');
        $this->db->from('instructor_profiles ip');
        $this->db->join('users u', 'u.id = ip.user_id');
        $this->db->where('ip.status', 'approved');
        $this->db->where('u.is_active', 0);
        $this->db->order_by('ip.name', 'ASC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $result = $query->result();
        return $result ?: [];
    }

    public function count_suspended_instructors() {
        $this->db->where('status', 'approved');
        $this->db->where('users.is_active', 0);
        $this->db->from('instructor_profiles');
        $this->db->join('users', 'users.id = instructor_profiles.user_id');
        return $this->db->count_all_results();
    }

    public function get_pending_instructors($limit, $offset) {
        $this->db->select('ip.id as instructor_id, ip.user_id, ip.name, ip.specialization, ip.experience, ip.bio, ip.profile_picture_path as image, u.email, ip.submitted_at, u.is_active');
        $this->db->from('instructor_profiles ip');
        $this->db->join('users u', 'u.id = ip.user_id');
        $this->db->where('ip.status', 'pending');
        $this->db->order_by('ip.name', 'ASC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $result = $query->result();
        return $result ?: [];
    }

    public function count_pending_instructors() {
        $this->db->where('status', 'pending');
        $this->db->from('instructor_profiles');
        return $this->db->count_all_results();
    }

    public function get_assigned_students_distinct($instructor_id) {
        $this->db->select('DISTINCT u.id as student_id, u.name, u.email, e.enrolled_at, e.enrollment_id, e.status as enrollment_status');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->where('e.instructor_id', $instructor_id); // Use instructor_id
        $this->db->where('c.is_active', 1);
        $this->db->where('c.approval_status', 'approved');
        $this->db->where('u.role', 'student');
        // Removed e.status = 'active' to include pending enrollments
        $this->db->order_by('e.enrolled_at', 'DESC');
        
        $query = $this->db->get();
        
        log_message('debug', 'Get Assigned Students Distinct Query: ' . $this->db->last_query());
        
        return $query->result();
    }

    public function count_assigned_students($instructor_id) {
        $this->db->select('COUNT(DISTINCT e.student_id) as total');
        $this->db->from('enrollments e');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->where('e.instructor_id', $instructor_id); // Use instructor_id
        $this->db->where('c.is_active', 1);
        $this->db->where('c.approval_status', 'approved');
        // Removed e.status = 'active' to include pending enrollments
        
        $result = $this->db->get()->row();
        return $result ? $result->total : 0;
    }
public function is_student_assigned($enrollment_id, $instructor_id) {
        $this->db->select('e.enrollment_id');
        $this->db->from('enrollments e');
        $this->db->where('e.enrollment_id', $enrollment_id);
        $this->db->where('e.assigned_instructor_id', $instructor_id);
        $this->db->where_in('e.status', ['active', 'approved']);
        $query = $this->db->get();
        
        log_message('debug', 'Is Student Assigned Query: ' . $this->db->last_query());
        log_message('debug', 'Enrollment ID: ' . $enrollment_id . ', Instructor ID: ' . $instructor_id . ', Found: ' . $query->num_rows());
        if ($query->num_rows() == 0) {
            $enrollment = $this->db->get_where('enrollments', ['enrollment_id' => $enrollment_id])->row();
            log_message('debug', 'Enrollment Details: ' . ($enrollment ? json_encode($enrollment) : 'Not found'));
        }
        
        return $query->num_rows() > 0;
    }
    
}