<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function register($user_data) {
        $required = ['name', 'email', 'password', 'role', 'created_at', 'updated_at'];
        foreach ($required as $field) {
            if (!isset($user_data[$field]) || empty($user_data[$field])) {
                log_message('error', "Missing required field: $field");
                return false;
            }
        }

        $user_data = array_merge([
            'qualification' => null,
            'bio' => null,
            'last_login' => null,
            'is_approved' => ($user_data['role'] == 'student') ? 1 : 0,
            'approval_status' => ($user_data['role'] == 'student') ? 'approved' : 'pending',
            'is_active' => 1,
            'approval_requested_at' => null,
            'approved_at' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
            'remember_token' => null
        ], $user_data);

        if (!password_get_info($user_data['password'])['algo']) {
            $user_data['password'] = password_hash($user_data['password'], PASSWORD_BCRYPT);
        }

        try {
            log_message('debug', 'Registering user with data: ' . json_encode($user_data));
            $this->db->insert('users', $user_data);
            if ($this->db->affected_rows() > 0) {
                log_message('debug', 'User registered successfully, ID: ' . $this->db->insert_id());
                return $this->db->insert_id();
            } else {
                $error = $this->db->error();
                log_message('error', 'Database error during registration: ' . json_encode($error));
                return false;
            }
        } catch (Exception $e) {
            log_message('error', 'Registration exception: ' . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password, $remember_me = false) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 0) {
            log_message('debug', 'No user found for email: ' . $email);
            return 'The email address you entered is not registered with us. Please check or register.';
        }

        $user = $query->row();
        log_message('debug', 'User found: ' . print_r($user, true));
        if (!password_verify($password, $user->password)) {
            log_message('debug', 'Password verification failed for email: ' . $email);
            return 'The password you entered is incorrect. Please try again.';
        }

        log_message('debug', 'Password verified for user ID: ' . $user->id);
        $this->update_user($user->id, ['last_login' => date('Y-m-d H:i:s')]);

        if ($remember_me) {
            $remember_token = bin2hex(random_bytes(32));
            $this->update_user($user->id, ['remember_token' => $remember_token]);
            $this->input->set_cookie([
                'name' => 'remember_me',
                'value' => $remember_token,
                'expire' => 86400 * 30, // 30 days
                'path' => '/',
                'secure' => false // Set to true if using HTTPS
            ]);
            $this->input->set_cookie([
                'name' => 'remember_me_email',
                'value' => $email,
                'expire' => 86400 * 30, // 30 days
                'path' => '/',
                'secure' => false
            ]);
            log_message('debug', 'Remember Me token set for user ID: ' . $user->id);
        }

        return $user;
    }

    public function get_user_by_remember_token($token) {
        $this->db->where('remember_token', $token);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }

    public function get_user($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->get('users')->row();
    }

    public function get_all_users() {
        $this->db->select('id, name, email, role, is_approved, created_at');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('users')->result();
    }

    public function get_user_with_details($user_id) {
        return $this->db->select('u.*, p.*')
                        ->from('users u')
                        ->join('instructor_profiles p', 'u.id = p.user_id', 'left')
                        ->where('u.id', $user_id)
                        ->get()
                        ->row();
    }

    public function update_user($user_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $user_id)
                        ->update('users', $data);
    }

    public function get_recent_users($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('users')->result();
    }

    public function get_pending_instructors($limit, $offset) {
        $this->db->select('ip.*, u.name, u.email, u.created_at');
        $this->db->from('instructor_profiles ip');
        $this->db->join('users u', 'u.id = ip.user_id');
        $this->db->where('ip.status', 'pending');
        $this->db->order_by('ip.submitted_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function update_instructor_status($user_id, $status, $feedback = null) {
        $this->db->where('user_id', $user_id);
        $data = ['status' => $status];
        if ($feedback !== null) {
            $data['feedback'] = $feedback;
        }
        return $this->db->update('instructor_profiles', $data);
    }

    public function approve_instructor($user_id) {
        $this->db->where('id', $user_id);
        $this->db->where('role', 'instructor');
        return $this->db->update('users', [
            'is_approved' => 1,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_approved_instructors() {
        $this->db->where('role', 'instructor');
        $this->db->where('is_approved', 1);
        $this->db->order_by('approved_at', 'DESC');
        $this->db->limit(10);
        return $this->db->get('users')->result();
    }

    public function count_pending_instructors() {
        $this->db->where('status', 'pending');
        return $this->db->count_all_results('instructor_profiles');
    }

    // In User_model.php
public function count_all_users() {
    return $this->db->count_all('users');
}

public function calculate_monthly_growth() {
    $current_month = $this->db->query("SELECT COUNT(*) as count FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->row()->count;
    $previous_month = $this->db->query("SELECT COUNT(*) as count FROM users WHERE MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))")->row()->count;
    
    if ($previous_month == 0) return 0;
    return round(($current_month - $previous_month) / $previous_month * 100, 1);
}

    public function get_instructor_performance($user_id) {
        $this->db->select('
            COUNT(DISTINCT courses.course_id) as total_courses,
            COUNT(DISTINCT enrollments.enrollment_id) as total_students,
            COUNT(DISTINCT CASE WHEN enrollments.status = "completed" THEN enrollments.enrollment_id END) as completed_students
        ');
        $this->db->from('users');
        $this->db->join('courses', 'courses.instructor_id = users.id AND courses.status = "published"', 'left');
        $this->db->join('enrollments', 'enrollments.course_id = courses.course_id', 'left');
        $this->db->where('users.id', $user_id);
        $query = $this->db->get();
        log_message('debug', 'Get Instructor Performance Query: ' . $this->db->last_query());
        return $query->row();
    }

    private function create_empty_performance() {
        return (object)[
            'total_courses' => 0,
            'total_students' => 0,
            'completed_students' => 0
        ];
    }

    public function get_instructors() {
        $this->db->where('role', 'instructor');
        $this->db->where('is_active', 1);
        return $this->db->get('users')->result();
    }

    public function get_user_by_id($user_id) {
        return $this->db->where('id', $user_id)
                       ->get('users')
                       ->row();
    }

    public function get_user_by_email($email) {
        return $this->db->where('email', $email)
                       ->get('users')
                       ->row();
    }

    public function insert_instructor_profile($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('instructor_profiles', $data);
    }

    public function update_instructor_profile($user_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('user_id', $user_id)
                        ->update('instructor_profiles', $data);
    }
}