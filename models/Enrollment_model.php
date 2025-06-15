
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollment_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

   public function get_pending_enrollments_with_instructors($search = '', $course_id = '', $status = 'pending_approval') {
    $this->db->select('e.*, s.name AS student_name, s.email AS student_email, c.course_name, pi.name AS preferred_instructor_name, ai.name AS assigned_instructor_name');
    $this->db->from('enrollments e');
    $this->db->join('users s', 's.id = e.student_id');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->join('users pi', 'pi.id = e.preferred_instructor_id', 'left');
    $this->db->join('users ai', 'ai.id = e.assigned_instructor_id', 'left');
    if ($search) {
        $this->db->group_start();
        $this->db->like('s.name', $search);
        $this->db->or_like('c.course_name', $search);
        $this->db->group_end();
    }
    if ($course_id) {
        $this->db->where('e.course_id', $course_id);
    }
    $this->db->where('e.status', $status); // Ensure status is applied
    $this->db->where('e.status !=', 'rejected'); // Exclude rejected enrollments
    $this->db->order_by('e.enrollment_date', 'ASC');
    $query = $this->db->get();
    log_message('debug', 'Pending Enrollments Query: ' . $this->db->last_query());
    return $query->result();
}

    public function get_active_enrollments($search = '', $course_id = '') {
        $this->db->select('e.*, s.name AS student_name, s.email AS student_email, c.course_name, ai.name AS instructor_name');
        $this->db->from('enrollments e');
        $this->db->join('users s', 's.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->join('users ai', 'ai.id = e.assigned_instructor_id', 'left');
        $this->db->where('e.status', 'active');
        if ($search) {
            $this->db->group_start();
            $this->db->like('s.name', $search);
            $this->db->or_like('c.course_name', $search);
            $this->db->group_end();
        }
        if ($course_id) {
            $this->db->where('e.course_id', $course_id);
        }
        $this->db->order_by('e.enrolled_at', 'DESC');
        $query = $this->db->get();
        log_message('debug', 'Active Enrollments Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function get_enrollment_details($enrollment_id) {
        $this->db->select('e.*, s.name AS student_name, s.email AS student_email, c.course_name, c.description, ai.name AS instructor_name, pi.name AS preferred_instructor_name');
        $this->db->from('enrollments e');
        $this->db->join('users s', 's.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->join('users ai', 'ai.id = e.assigned_instructor_id', 'left');
        $this->db->join('users pi', 'pi.id = e.preferred_instructor_id', 'left');
        $this->db->where('e.enrollment_id', $enrollment_id);
        return $this->db->get()->row();
    }

    public function update_enrollment($enrollment_id, $data) {
        $this->db->where('enrollment_id', $enrollment_id);
        return $this->db->update('enrollments', $data);
    }

    public function get_enrollment($enrollment_id) {
        $this->db->where('enrollment_id', $enrollment_id);
        return $this->db->get('enrollments')->row();
    }

    public function get_enrollment_with_preferred_instructor($enrollment_id) {
        $this->db->select('e.*, s.name AS student_name, c.course_name, pi.name AS preferred_instructor_name');
        $this->db->from('enrollments e');
        $this->db->join('users s', 's.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->join('users pi', 'pi.id = e.preferred_instructor_id', 'left');
        $this->db->where('e.enrollment_id', $enrollment_id);
        return $this->db->get()->row();
    }

    public function create_enrollment($data) {
        // Ensure preferred_days is properly formatted
        if (isset($data['preferred_days']) && is_array($data['preferred_days'])) {
            $data['preferred_days'] = implode(',', $data['preferred_days']);
        }

        // Validate course_id
        if (!isset($data['course_id'])) {
            log_message('error', 'Course ID missing in enrollment data: ' . json_encode($data));
            return false;
        }

        // Fetch instructor_id from courses
        $course = $this->db->select('instructor_id')->get_where('courses', ['course_id' => $data['course_id']])->row();
        if (!$course) {
            log_message('error', 'Course not found: course_id=' . $data['course_id']);
            return false;
        }

        // Set assigned_instructor_id and status
        $data['assigned_instructor_id'] = $course->instructor_id ?? null;
        $data['status'] = 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert('enrollments', $data);
        
        if ($this->db->affected_rows() > 0) {
            $enrollment_id = $this->db->insert_id();
            log_message('debug', 'Created enrollment: ' . $enrollment_id . ', Data: ' . json_encode($data));
            return $enrollment_id;
        }
        
        log_message('error', 'Failed to create enrollment: ' . $this->db->error()['message'] . ', Data: ' . json_encode($data));
        return false;
    }
    public function is_enrolled($student_id, $course_id) {
        $this->db->where('student_id', $student_id);
        $this->db->where('course_id', $course_id);
        return $this->db->get('enrollments')->num_rows() > 0;
    }

    public function get_student_courses($user_id) {
        $this->db->select('enrollments.enrollment_id, enrollments.student_id, enrollments.course_id, enrollments.enrollment_date, enrollments.status, enrollments.progress, enrollments.learning_goal, enrollments.current_level, enrollments.preferred_schedule, enrollments.preferred_days, enrollments.class_duration, enrollments.notes, enrollments.admin_assign_instructor, enrollments.last_accessed, enrollments.assigned_instructor_id, enrollments.enrolled_at, courses.course_name, courses.description, courses.level, users.name as instructor_name');
        $this->db->from('enrollments');
        $this->db->join('courses', 'courses.course_id = enrollments.course_id', 'left');
        $this->db->join('users', 'users.id = enrollments.assigned_instructor_id', 'left'); // Changed to users table
        $this->db->where('enrollments.student_id', $user_id);
        $this->db->where_in('enrollments.status', ['approved', 'active']); // Added status filter
        return $this->db->get()->result();
    }

    public function get_overall_progress($student_id) {
        $this->db->select('course_id');
        $this->db->where('student_id', $student_id);
        $this->db->where_in('status', ['approved', 'active']);
        $enrolled_courses = $this->db->get('enrollments')->result_array();
        if (empty($enrolled_courses)) {
            return 0;
        }
        $course_ids = array_column($enrolled_courses, 'course_id');
        $this->db->where_in('course_id', $course_ids);
        $total_lessons = $this->db->count_all_results('lessons');
        if ($total_lessons > 0) {
            $this->db->where('student_id', $student_id);
            $completed_lessons = $this->db->count_all_results('student_lessons');
            return ($completed_lessons / $total_lessons) * 100;
        }
        return 0;
    }
    public function get_all_enrollments($search = '', $course_id = '') {
    $this->db->select('e.*, s.name AS student_name, s.email AS student_email, c.course_name, ai.name AS assigned_instructor_name');
    $this->db->from('enrollments e');
    $this->db->join('users s', 's.id = e.student_id');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->join('users ai', 'ai.id = e.assigned_instructor_id', 'left');
    if ($search) {
        $this->db->group_start();
        $this->db->like('s.name', $search);
        $this->db->or_like('c.course_name', $search);
        $this->db->group_end();
    }
    if ($course_id) {
        $this->db->where('e.course_id', $course_id);
    }
    $this->db->order_by('e.enrollment_date', 'DESC');
    $query = $this->db->get();
    log_message('debug', 'All Enrollments Query: ' . $this->db->last_query());
    return $query->result();
}
public function get_pending_assignments($search = '', $course_id = '') {
    log_message('debug', 'get_pending_assignments called with search: ' . $search . ', course_id: ' . $course_id);
    $this->db->select('e.*, s.name AS student_name, s.email AS student_email, c.course_name, ai.name AS assigned_instructor_name');
    $this->db->from('enrollments e');
    $this->db->join('users s', 's.id = e.student_id');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->join('users ai', 'ai.id = e.assigned_instructor_id', 'left');
    $this->db->where('e.status', 'pending_approval');
    $this->db->where('e.assigned_instructor_id IS NULL');
    if ($search) {
        $this->db->group_start();
        $this->db->like('s.name', $search);
        $this->db->or_like('c.course_name', $search);
        $this->db->group_end();
    }
    if ($course_id) {
        $this->db->where('e.course_id', $course_id);
    }
    $this->db->order_by('e.enrollment_date', 'ASC');
    $query = $this->db->get();
    log_message('debug', 'Pending Assignments Query: ' . $this->db->last_query());
    $result = $query->result();
    log_message('debug', 'Pending Assignments Result Count: ' . count($result));
    return $result;
}
public function approve_enrollment($enrollment_id, $instructor_id = null) {
        $enrollment = $this->db->get_where('enrollments', ['enrollment_id' => $enrollment_id])->row();
        if (!$enrollment) {
            log_message('error', 'Enrollment not found: ' . $enrollment_id);
            return false;
        }

        // Use provided instructor_id or fetch from course
        if (!$instructor_id && $enrollment->assigned_instructor_id) {
            $instructor_id = $enrollment->assigned_instructor_id;
        } elseif (!$instructor_id) {
            $course = $this->db->select('instructor_id')->get_where('courses', ['course_id' => $enrollment->course_id])->row();
            $instructor_id = $course->instructor_id ?? null;
        }

        if (!$instructor_id) {
            log_message('error', 'No instructor assigned for enrollment_id: ' . $enrollment_id);
            return false;
        }

        $data = [
            'assigned_instructor_id' => $instructor_id,
            'status' => 'active',
            'approved_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('enrollment_id', $enrollment_id);
        $result = $this->db->update('enrollments', $data);
        
        log_message('debug', 'Approved enrollment: ' . $enrollment_id . ', Instructor ID: ' . $instructor_id);
        return $result;
    }
public function get_instructor_courses($instructor_id) {
    $this->db->select('c.*, c.course_name AS course_title, COUNT(DISTINCT e.enrollment_id) as student_count');
    $this->db->from('courses c');
    $this->db->join('enrollments e', 'e.course_id = c.course_id', 'left');
    $this->db->where('e.assigned_instructor_id', $instructor_id);
    $this->db->where('c.is_active', 1);
    $this->db->where('c.approval_status', 'approved');
    $this->db->group_by('c.course_id');
    $this->db->order_by('c.created_at', 'DESC');
    
    $query = $this->db->get();
    
    // Debug logging
    log_message('debug', 'Get Instructor Courses Query: ' . $this->db->last_query());
    log_message('debug', 'Instructor ID used: ' . $instructor_id);
    
    return $query->result();
}

public function get_assigned_students($instructor_id) {
    $this->db->select('u.id as student_id, u.name, u.email, c.course_name, c.course_id, e.enrolled_at, e.enrollment_id, e.status as enrollment_status');
    $this->db->from('enrollments e');
    $this->db->join('users u', 'u.id = e.student_id');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->where('e.assigned_instructor_id', $instructor_id);
    $this->db->where('c.is_active', 1);
    $this->db->where('c.approval_status', 'approved');
    $this->db->where('u.role', 'student'); // Ensure we're only getting students
    $this->db->order_by('e.enrolled_at', 'DESC');
    
    $query = $this->db->get();
    
    // Debug logging
    log_message('debug', 'Get Assigned Students Query: ' . $this->db->last_query());
    log_message('debug', 'Instructor ID used: ' . $instructor_id);
    log_message('debug', 'Number of students found: ' . $query->num_rows());
    
    $students = [];
    foreach ($query->result() as $row) {
        $students[] = (object)[
            'student_id' => $row->student_id,
            'student_name' => $row->name,
            'student_email' => $row->email,
            'course_name' => $row->course_name,
            'course_id' => $row->course_id,
            'enrollment_date' => $row->enrolled_at,
            'enrollment_id' => $row->enrollment_id,
            'enrollment_status' => $row->enrollment_status
        ];
    }
    
    // Remove duplicates based on enrollment_id
    return array_unique($students, SORT_REGULAR);
}

// Alternative method to get students without duplicates
public function get_assigned_students_distinct($instructor_id) {
    $this->db->select('DISTINCT u.id as student_id, u.name, u.email, e.enrolled_at, e.enrollment_id, e.status as enrollment_status');
    $this->db->from('enrollments e');
    $this->db->join('users u', 'u.id = e.student_id');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->where('e.assigned_instructor_id', $instructor_id);
    $this->db->where('c.is_active', 1);
    $this->db->where('c.approval_status', 'approved');
    $this->db->where('u.role', 'student');
    $this->db->order_by('e.enrolled_at', 'DESC');
    
    $query = $this->db->get();
    
    // Debug logging
    log_message('debug', 'Get Assigned Students Distinct Query: ' . $this->db->last_query());
    
    return $query->result();
}

// Method to count students for an instructor
public function count_assigned_students($instructor_id) {
    $this->db->select('COUNT(DISTINCT e.student_id) as total');
    $this->db->from('enrollments e');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->where('e.assigned_instructor_id', $instructor_id);
    $this->db->where('c.is_active', 1);
    $this->db->where('c.approval_status', 'approved');
    
    $result = $this->db->get()->row();
    return $result ? $result->total : 0;
}
// Add to Enrollment_model.php
public function is_student_enrolled($student_id, $course_id, $instructor_id) {
    $this->db->where('student_id', $student_id);
    $this->db->where('course_id', $course_id);
    $this->db->where('assigned_instructor_id', $instructor_id);
    $this->db->where_in('status', ['active', 'approved']);
    return $this->db->get('enrollments')->num_rows() > 0;
}
// Add to Enrollment_model.php
public function get_course_students($course_id, $instructor_id) {
    $this->db->select('u.id, u.name');
    $this->db->from('enrollments e');
    $this->db->join('users u', 'u.id = e.student_id');
    $this->db->where('e.course_id', $course_id);
    $this->db->where('e.assigned_instructor_id', $instructor_id);
    $this->db->where_in('e.status', ['active', 'approved']);
    $this->db->where('u.role', 'student');
  
  return $this->db->get()->result();
}
public function count_all_enrollments() {
    return $this->db->count_all('enrollments');
}

public function count_active_enrollments() {
    $this->db->where('status', 'active');
    return $this->db->count_all_results('enrollments');
}

public function count_pending_enrollments() {
    $this->db->where('status', 'pending_approval');
    return $this->db->count_all_results('enrollments');
}

public function get_recent_enrollments($limit = 5) {
    $this->db->select('e.*, u.name, u.email, c.course_name');
    $this->db->from('enrollments e');
    $this->db->join('users u', 'u.id = e.student_id');
    $this->db->join('courses c', 'c.course_id = e.course_id');
    $this->db->order_by('e.enrolled_at', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result();
}
}
