<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get the count of active students.
     * This assumes you have a way to define 'active' students,
     * such as a status field in your 'users' table where role is 'student'.
     * Modify the WHERE clause according to your database structure.
     *
     * @return int The number of active students.
     */
    public function get_active_students() {
        $this->db->where('role', 'student');
        // Add your condition for active students here.
        // For example, if you have an 'is_active' column:
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('users'); // Assuming student data is in the 'users' table
    }

    /**
     * Calculate the percentage growth in the number of students
     * between the current month and the last month.
     * This assumes you have a 'created_at' timestamp in your 'users' table.
     *
     * @return int The percentage growth in student numbers.
     */
    public function get_student_growth() {
        $this->db->where('MONTH(created_at)', date('m'))
                 ->where('YEAR(created_at)', date('Y'))
                 ->where('role', 'student');
        $this_month = $this->db->count_all_results('users');

        $this->db->where('MONTH(created_at)', date('m', strtotime('-1 month')))
                 ->where('YEAR(created_at)', date('Y', strtotime('-1 month')))
                 ->where('role', 'student');
        $last_month = $this->db->count_all_results('users');

        if ($last_month == 0) {
            return $this_month > 0 ? 100 : 0;
        }

        return round((($this_month - $last_month) / $last_month) * 100);
    }

    /**
     * Example: Get a list of all students.
     *
     * @return array List of student objects.
     */
    public function get_all_students() {
        $this->db->where('role', 'student');
        return $this->db->get('users')->result(); // Assuming student data is in the 'users' table
    }

    /**
     * Example: Get student details by their user ID.
     *
     * @param int $student_id The ID of the student (user ID).
     * @return object|null Student object if found, null otherwise.
     */
    public function get_student($student_id) {
        $this->db->where('id', $student_id);
        $this->db->where('role', 'student');
        return $this->db->get('users')->row(); // Assuming student data is in the 'users' table
    }

    public function get_students_by_course($course_id) {
        $this->db->select('u.name, u.email, e.enrolled_at, e.enrollment_id');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->where('e.course_id', $course_id);
        $this->db->where('u.role', 'student');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_student_by_enrollment($enrollment_id) {
        $this->db->select('u.id as student_id, u.name as student_name, u.email as student_email, 
                          c.course_id, c.course_name, e.enrolled_at as enrollment_date, e.enrollment_id');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->where('e.enrollment_id', $enrollment_id);
        return $this->db->get()->row();
    }

    public function get_student_progress($enrollment_id) {
        // Get course progress
        $this->db->select('progress as course_progress');
        $this->db->from('enrollments');
        $this->db->where('enrollment_id', $enrollment_id);
        $query = $this->db->get();
        log_message('debug', 'Get Student Progress Query: ' . $this->db->last_query());
        $progress = $query->row();
        
        // Get assignment stats
        $assignments = (object)['completed_assignments' => 0, 'average_score' => 0];
        if ($this->db->table_exists('assignment_submissions')) {
            $this->db->select('COUNT(*) as completed_assignments, AVG(grade) as average_score');
            $this->db->from('assignment_submissions');
            $this->db->where('enrollment_id', $enrollment_id);
            $this->db->where('status', 'graded');
            $query = $this->db->get();
            log_message('debug', 'Assignment Stats Query: ' . $this->db->last_query());
            $assignments = $query->row();
        } else {
            log_message('error', 'Table assignment_submissions does not exist');
        }
        
        // Get attendance stats
        $attendance = (object)['total_classes' => 0, 'attended_classes' => 0];
        if ($this->db->table_exists('class_attendance')) {
            $this->db->select('COUNT(*) as total_classes, SUM(attended) as attended_classes');
            $this->db->from('class_attendance');
            $this->db->where('enrollment_id', $enrollment_id);
            $query = $this->db->get();
            log_message('debug', 'Attendance Stats Query: ' . $this->db->last_query());
            $attendance = $query->row();
        } else {
            log_message('error', 'Table class_attendance does not exist');
        }
        
        return (object)[
            'course_progress' => $progress->course_progress ?? 0,
            'last_lesson' => 'N/A',
            'last_lesson_date' => null,
            'completed_assignments' => $assignments->completed_assignments ?? 0,
            'average_score' => $assignments->average_score ? round($assignments->average_score, 1) : 0,
            'attendance_rate' => $attendance->total_classes ? round(($attendance->attended_classes / $attendance->total_classes) * 100) : 0,
            'assignment_completion' => 0 // You can implement this based on your requirements
        ];
    }

    public function get_upcoming_classes($enrollment_id) {
        $this->db->select('c.class_id, c.title, c.class_date, c.start_time, c.end_time, c.status');
        $this->db->from('classes c');
        $this->db->join('enrollments e', 'e.course_id = c.course_id');
        $this->db->where('e.enrollment_id', $enrollment_id);
        $this->db->where('c.class_date >=', date('Y-m-d'));
        $this->db->order_by('c.class_date, c.start_time');
        return $this->db->get()->result();
    }
    public function get_new_students_this_month() {
    $this->db->where('MONTH(created_at)', date('m'));
    $this->db->where('YEAR(created_at)', date('Y'));
    $this->db->where('role', 'student');
    return $this->db->count_all_results('users');
}

public function get_recent_students($limit = 5) {
    $this->db->where('role', 'student');
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit($limit);
    return $this->db->get('users')->result();
}

   
}