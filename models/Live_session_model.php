<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_session_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_session($session_data, $student_id) {
        $this->db->trans_start();

        $session_data['max_students'] = 1;
        if (empty($session_data['meeting_link'])) {
            $session_data['meeting_link'] = 'https://meet.jit.si/quran-learning-session-' . uniqid();
        }

        if (!$this->db->insert('live_sessions', $session_data)) {
            log_message('error', 'DB Error creating session: ' . print_r($this->db->error(), true));
            $this->db->trans_rollback();
            return false;
        }
        $session_id = $this->db->insert_id();

        $attendee_data = [
            'session_id' => $session_id,
            'student_id' => $student_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (!$this->db->insert('session_attendees', $attendee_data)) {
            log_message('error', 'DB Error adding attendee: ' . print_r($this->db->error(), true));
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_complete();
        return $session_id;
    }

    public function get_session($session_id) {
        $this->db->select('s.*, sa.student_id');
        $this->db->from('live_sessions s');
        $this->db->join('session_attendees sa', 'sa.session_id = s.session_id', 'left');
        $this->db->where('s.session_id', $session_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_instructor_sessions($instructor_id) {
        $this->db->select('s.session_id, s.title, s.start_time, s.end_time, s.meeting_link, s.course_id, c.course_name, sa.student_id, u.name as student_name');
        $this->db->from('live_sessions s');
        $this->db->join('courses c', 'c.course_id = s.course_id', 'left');
        $this->db->join('session_attendees sa', 'sa.session_id = s.session_id', 'left');
        $this->db->join('users u', 'u.id = sa.student_id', 'left');
        $this->db->where('s.instructor_id', $instructor_id);
        $this->db->where('s.start_time >=', date('Y-m-d H:i:s'));
        $this->db->order_by('s.start_time', 'ASC');
        return $this->db->get()->result();
    }

   public function get_student_sessions($student_id) {
    $this->db->select('s.session_id, s.title, s.start_time, s.end_time, s.meeting_link, s.instructor_id, c.course_name, u.name as instructor_name');
    $this->db->from('live_sessions s');
    $this->db->join('courses c', 'c.course_id = s.course_id', 'left');
    $this->db->join('session_attendees sa', 'sa.session_id = s.session_id');
    $this->db->join('users u', 'u.id = s.instructor_id', 'left');
    $this->db->where('sa.student_id', $student_id);
    $this->db->where('s.start_time >=', date('Y-m-d H:i:s'));
    $this->db->order_by('s.start_time', 'ASC');
    return $this->db->get()->result();
}

    public function update_session($session_id, $data) {
        $this->db->where('session_id', $session_id);
        if (!$this->db->update('live_sessions', $data)) {
            log_message('error', 'DB Error updating session: ' . print_r($this->db->error(), true));
            return false;
        }
        return true;
    }

    public function mark_attendance($session_id, $student_id) {
        $this->db->where('session_id', $session_id);
        $this->db->where('student_id', $student_id);
        $this->db->update('session_attendees', ['joined_at' => date('Y-m-d H:i:s')]);
        return $this->db->affected_rows() > 0;
    }
}