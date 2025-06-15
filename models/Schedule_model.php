<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_instructor_classes($instructor_id) {
        $this->db->select('c.id, c.enrollment_id, c.course_id, c.instructor_id, c.student_id, c.start_time, c.end_time, c.status, c.created_at, c.updated_at');
        $this->db->select('co.course_name, u.name as student_name');
        $this->db->select('a.attendance_status, a.notes as attendance_notes');
        $this->db->from('classes c');
        $this->db->join('courses co', 'co.course_id = c.course_id');
        $this->db->join('users u', 'u.user_id = c.student_id');
        $this->db->join('attendance a', 'a.class_id = c.id', 'left');
        $this->db->where('c.instructor_id', $instructor_id);
        $this->db->where('DATE(c.start_time) >=', date('Y-m-d'));
        $this->db->order_by('c.start_time');
        $query = $this->db->get();
        log_message('debug', 'Get Instructor Classes Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function get_calendar_events($instructor_id, $start_date, $end_date) {
        $this->db->select('c.id, c.enrollment_id, c.course_id, c.instructor_id, c.student_id, c.start_time, c.end_time, c.status');
        $this->db->select('co.course_name, u.name as student_name');
        $this->db->select('a.attendance_status');
        $this->db->from('classes c');
        $this->db->join('courses co', 'co.course_id = c.course_id');
        $this->db->join('users u', 'u.user_id = c.student_id');
        $this->db->join('attendance a', 'a.class_id = c.id', 'left');
        $this->db->where('c.instructor_id', $instructor_id);
        
        if ($start_date && $end_date) {
            $this->db->where('DATE(c.start_time) >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('DATE(c.start_time) <=', date('Y-m-d', strtotime($end_date)));
        }
        
        $query = $this->db->get();
        log_message('debug', 'Get Calendar Events Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function get_class($class_id) {
        $this->db->select('c.id, c.enrollment_id, c.course_id, c.instructor_id, c.student_id, c.start_time, c.end_time, c.status, c.created_at, c.updated_at');
        $this->db->select('a.attendance_status, a.notes as attendance_notes, a.marked_at');
        $this->db->from('classes c');
        $this->db->join('attendance a', 'a.class_id = c.id', 'left');
        $this->db->where('c.id', $class_id);
        return $this->db->get()->row();
    }

    public function create_class($data) {
        $required_fields = ['enrollment_id', 'course_id', 'instructor_id', 'student_id', 'start_time', 'end_time'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                log_message('error', "Missing required field: $field");
                return false;
            }
        }

        if (!isset($data['status'])) {
            $data['status'] = 'scheduled';
        }

        if (!$this->db->insert('classes', $data)) {
            log_message('error', 'DB Error creating class: ' . print_r($this->db->error(), true));
            return false;
        }
        
        $insert_id = $this->db->insert_id();
        log_message('debug', 'Class created with ID: ' . $insert_id);
        return $insert_id;
    }

    public function update_class($class_id, $data) {
        $this->db->where('id', $class_id);
        if (!$this->db->update('classes', $data)) {
            log_message('error', 'DB Error updating class: ' . print_r($this->db->error(), true));
            return false;
        }
        log_message('debug', 'Class updated: ' . $class_id);
        return true;
    }

    public function mark_attendance($data) {
        $existing = $this->db->get_where('attendance', ['class_id' => $data['class_id']])->row();
        
        if ($existing) {
            $this->db->where('class_id', $data['class_id']);
            $result = $this->db->update('attendance', $data);
        } else {
            $result = $this->db->insert('attendance', $data);
        }

        if ($result && $this->db->affected_rows() > 0) {
            log_message('debug', 'Attendance marked: ' . print_r($data, true));
            return true;
        }
        
        log_message('error', 'Failed to mark attendance: ' . print_r($data, true));
        return false;
    }

    public function get_attendance($class_id) {
        $this->db->select('attendance_id, class_id, enrollment_id, student_id, instructor_id, attendance_status, marked_at, notes');
        $this->db->from('attendance');
        $this->db->where('class_id', $class_id);
        $query = $this->db->get();
        log_message('debug', 'Get Attendance Query: ' . $this->db->last_query());
        return $query->row();
    }

    public function is_time_slot_available($instructor_id, $start_time, $end_time, $exclude_class_id = null) {
        if (!$this->is_valid_datetime($start_time) || !$this->is_valid_datetime($end_time)) {
            log_message('error', "Invalid datetime format: start_time=$start_time, end_time=$end_time");
            return false;
        }

        $this->db->where('instructor_id', $instructor_id);
        $this->db->where('status !=', 'cancelled');
        $this->db->where("(start_time < '$end_time' AND end_time > '$start_time')");
        
        if ($exclude_class_id) {
            $this->db->where('id !=', $exclude_class_id);
        }
        
        $overlap = $this->db->get('classes')->num_rows();
        log_message('debug', 'Overlap Check Query: ' . $this->db->last_query());

        if ($overlap > 0) {
            log_message('debug', "Time slot conflict for instructor_id: $instructor_id from $start_time to $end_time");
            return false;
        }

        $day_of_week = strtolower(date('l', strtotime($start_time)));
        $start_time_only = date('H:i:s', strtotime($start_time));
        $end_time_only = date('H:i:s', strtotime($end_time));
        
        $this->db->where('instructor_id', $instructor_id);
        $this->db->where('day_of_week', $day_of_week);
        $this->db->where('start_time <=', $start_time_only);
        $this->db->where('end_time >=', $end_time_only);
        $available = $this->db->get('instructor_availability')->num_rows();
        
        log_message('debug', 'Availability Check Query: ' . $this->db->last_query());

        return $available > 0;
    }

    private function is_valid_datetime($datetime) {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $d && $d->format('Y-m-d H:i:s') === $datetime;
    }

    public function generate_next_class($enrollment_id, $instructor_id) {
        // Fetch enrollment details
        $this->db->select('e.student_id, e.course_id, e.preferred_days, e.preferred_time, u.name as student_name');
        $this->db->from('enrollment e');
        $this->db->join('users u', 'u.user_id = e.student_id');
        $this->db->where('e.enrollment_id', $enrollment_id);
        $enrollment = $this->db->get()->row();
        
        if (!$enrollment || empty($enrollment->preferred_days) || !$enrollment->preferred_time) {
            log_message('error', 'Invalid enrollment or missing preferred days/time for enrollment_id: ' . $enrollment_id);
            return false;
        }

        // Decode preferred days (assuming JSON array)
        $preferred_days = json_decode($enrollment->preferred_days, true);
        if (!is_array($preferred_days)) {
            log_message('error', 'Invalid preferred_days format for enrollment_id: ' . $enrollment_id);
            return false;
        }

        // Find the next available day
        $current_date = new DateTime();
        $next_class_date = null;
        for ($i = 1; $i <= 7; $i++) {
            $check_date = (clone $current_date)->modify("+$i day");
            $day_of_week = strtolower($check_date->format('l'));
            if (in_array(ucfirst($day_of_week), $preferred_days)) {
                $next_class_date = $check_date;
                break;
            }
        }

        if (!$next_class_date) {
            log_message('error', 'No matching preferred day found for enrollment_id: ' . $enrollment_id);
            return false;
        }

        // Combine date with preferred time
        $start_time = $next_class_date->format('Y-m-d') . ' ' . $enrollment->preferred_time;
        $end_time = (new DateTime($start_time))->modify('+1 hour')->format('Y-m-d H:i:s');

        // Check instructor availability
        if (!$this->is_time_slot_available($instructor_id, $start_time, $end_time)) {
            log_message('error', 'Time slot not available for instructor_id: ' . $instructor_id . ' at ' . $start_time);
            return false;
        }

        // Create the next class
        $data = [
            'enrollment_id' => $enrollment_id,
            'course_id' => $enrollment->course_id,
            'instructor_id' => $instructor_id,
            'student_id' => $enrollment->student_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'scheduled'
        ];

        $class_id = $this->create_class($data);
        if ($class_id) {
            log_message('debug', 'Next class scheduled for enrollment_id: ' . $enrollment_id . ' at ' . $start_time);
            return $class_id;
        }
        return false;
    }

    public function get_student_classes($student_id) {
        $this->db->select('c.id, c.enrollment_id, c.course_id, c.instructor_id, c.student_id, c.start_time, c.end_time, c.status');
        $this->db->select('co.course_name, u.name as instructor_name');
        $this->db->select('a.attendance_status, a.notes as attendance_notes');
        $this->db->from('classes c');
        $this->db->join('courses co', 'co.course_id = c.course_id');
        $this->db->join('users u', 'u.user_id = c.instructor_id');
        $this->db->join('attendance a', 'a.class_id = c.id', 'left');
        $this->db->where('c.student_id', $student_id);
        $this->db->order_by('c.start_time', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_attendance_summary($student_id, $course_id = null, $date_from = null, $date_to = null) {
        $this->db->select('COUNT(*) as total_classes');
        $this->db->select('SUM(CASE WHEN a.attendance_status = "present" THEN 1 ELSE 0 END) as present_count');
        $this->db->select('SUM(CASE WHEN a.attendance_status = "absent" THEN 1 ELSE 0 END) as absent_count');
        $this->db->from('classes c');
        $this->db->join('attendance a', 'a.class_id = c.id', 'left');
        $this->db->where('c.student_id', $student_id);
        $this->db->where('c.status', 'completed');
        
        if ($course_id) {
            $this->db->where('c.course_id', $course_id);
        }
        
        if ($date_from) {
            $this->db->where('DATE(c.start_time) >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('DATE(c.start_time) <=', $date_to);
        }
        
        return $this->db->get()->row();
    }
}