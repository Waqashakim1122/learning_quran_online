<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_enrollment_id_by_message($message_id) {
        $this->db->select('c.enrollment_id');
        $this->db->from('messages m');
        $this->db->join('conversations c', 'c.conversation_id = m.conversation_id');
        $this->db->where('m.message_id', $message_id);
        $query = $this->db->get();
        
        log_message('debug', 'Get Enrollment ID by Message Query: ' . $this->db->last_query());
        return $query->row()->enrollment_id ?? null;
    }

    public function get_assigned_students($instructor_id) {
        $this->db->select('e.enrollment_id, u.name as student_name, c.course_name, conv.last_message_at');
        $this->db->select('(SELECT m.message_text 
                           FROM messages m 
                           WHERE m.conversation_id = conv.conversation_id 
                           ORDER BY m.sent_at DESC 
                           LIMIT 1) as latest_message');
        $this->db->select('(SELECT COUNT(*) 
                           FROM messages m 
                           WHERE m.conversation_id = conv.conversation_id 
                           AND m.recipient_id = e.student_id 
                           AND m.is_read = 0) as unread_count');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.student_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->join('conversations conv', 'conv.enrollment_id = e.enrollment_id', 'left');
        $this->db->where('e.assigned_instructor_id', $instructor_id);
        $this->db->where_in('e.status', ['active', 'approved']);
        $query = $this->db->get();
        
        log_message('debug', 'Get Assigned Students Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function get_assigned_instructors($student_id) {
        $this->db->select('e.enrollment_id, u.name as instructor_name, c.course_name, conv.last_message_at');
        $this->db->select('(SELECT m.message_text 
                           FROM messages m 
                           WHERE m.conversation_id = conv.conversation_id 
                           ORDER BY m.sent_at DESC 
                           LIMIT 1) as latest_message');
        $this->db->select('(SELECT COUNT(*) 
                           FROM messages m 
                           WHERE m.conversation_id = conv.conversation_id 
                           AND m.recipient_id = e.student_id 
                           AND m.is_read = 0) as unread_count');
        $this->db->from('enrollments e');
        $this->db->join('users u', 'u.id = e.assigned_instructor_id');
        $this->db->join('courses c', 'c.course_id = e.course_id');
        $this->db->join('conversations conv', 'conv.enrollment_id = e.enrollment_id', 'left');
        $this->db->where('e.student_id', $student_id);
        $this->db->where_in('e.status', ['active', 'approved']);
        $query = $this->db->get();
        
        log_message('debug', 'Get Assigned Instructors Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function get_conversation_by_enrollment($enrollment_id) {
        $this->db->select('c.*, u.name as student_name, co.course_name, i.name as instructor_name');
        $this->db->from('conversations c');
        $this->db->join('enrollments e', 'e.enrollment_id = c.enrollment_id');
        $this->db->join('users u', 'u.id = c.student_id');
        $this->db->join('users i', 'i.id = c.instructor_id');
        $this->db->join('courses co', 'co.course_id = c.course_id');
        $this->db->where('c.enrollment_id', $enrollment_id);
        $query = $this->db->get();
        
        log_message('debug', 'Get Conversation by Enrollment Query: ' . $this->db->last_query());
        return $query->row();
    }

    public function create_conversation($data) {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['last_message_at'])) {
            $data['last_message_at'] = date('Y-m-d H:i:s');
        }
        $this->db->insert('conversations', $data);
        if ($this->db->affected_rows() > 0) {
            $conversation_id = $this->db->insert_id();
            log_message('debug', 'Created conversation: ' . $conversation_id . ', Data: ' . json_encode($data));
            return $conversation_id;
        }
        log_message('error', 'Failed to create conversation: ' . $this->db->error()['message'] . ', Data: ' . json_encode($data));
        return false;
    }

    public function get_conversation($conversation_id) {
        $this->db->select('c.*, u.name as student_name, i.name as instructor_name, co.course_name');
        $this->db->from('conversations c');
        $this->db->join('users u', 'u.id = c.student_id');
        $this->db->join('users i', 'i.id = c.instructor_id');
        $this->db->join('courses co', 'co.course_id = c.course_id');
        $this->db->where('c.conversation_id', $conversation_id);
        $query = $this->db->get();
        
        log_message('debug', 'Get Conversation Query: ' . $this->db->last_query());
        return $query->row();
    }

    public function get_conversation_for_student($conversation_id) {
        $this->db->select('c.*, u.name as instructor_name, s.name as student_name, co.course_name');
        $this->db->from('conversations c');
        $this->db->join('users u', 'u.id = c.instructor_id');
        $this->db->join('users s', 's.id = c.student_id');
        $this->db->join('courses co', 'co.course_id = c.course_id');
        $this->db->where('c.conversation_id', $conversation_id);
        $query = $this->db->get();
        
        log_message('debug', 'Get Conversation for Student Query: ' . $this->db->last_query());
        return $query->row();
    }

    public function get_messages($conversation_id) {
        try {
            $this->db->select('m.*, u.name as sender_name');
            $this->db->from('messages m');
            $this->db->join('users u', 'u.id = m.sender_id', 'left');
            $this->db->where('m.conversation_id', $conversation_id);
            $this->db->order_by('m.sent_at', 'ASC');
            $query = $this->db->get();
            
            log_message('debug', 'Get Messages Query: ' . $this->db->last_query());
            if ($query === false) {
                log_message('error', 'Query failed for conversation_id: ' . $conversation_id . ', Error: ' . $this->db->error()['message']);
                return false;
            }
            return $query->result() ?: [];
        } catch (Exception $e) {
            log_message('error', 'Exception in get_messages for conversation_id: ' . $conversation_id . ', Error: ' . $e->getMessage());
            return false;
        }
    }

    public function send_message($data) {
        $data['sent_at'] = date('Y-m-d H:i:s');
        $data['is_read'] = 0;
        $this->db->insert('messages', $data);
        if ($this->db->affected_rows() > 0) {
            log_message('debug', 'Sent message: ' . json_encode($data));
            return true;
        }
        log_message('error', 'Failed to send message: ' . $this->db->error()['message'] . ', Data: ' . json_encode($data));
        return false;
    }

    public function mark_messages_as_read($conversation_id, $recipient_id) {
        $this->db->where('conversation_id', $conversation_id);
        $this->db->where('recipient_id', $recipient_id);
        $this->db->where('is_read', 0);
        $this->db->update('messages', ['is_read' => 1]);
        
        log_message('debug', 'Mark Messages as Read Query: ' . $this->db->last_query());
    }

    public function update_conversation_timestamp($conversation_id) {
        $this->db->where('conversation_id', $conversation_id);
        $this->db->update('conversations', ['last_message_at' => date('Y-m-d H:i:s')]);
        
        log_message('debug', 'Update Conversation Timestamp Query: ' . $this->db->last_query());
    }

    public function get_unread_message_count($recipient_id) {
        $this->db->where('recipient_id', $recipient_id);
        $this->db->where('is_read', 0);
        $count = $this->db->count_all_results('messages');
        
        log_message('debug', 'Unread Message Count Query: ' . $this->db->last_query());
        return $count;
    }

    public function get_recent_messages($instructor_id, $limit = 5) {
        $this->db->select('m.message_id, m.message_text as message_content, m.sent_at, u.name as student_name, m.is_read');
        $this->db->from('messages m');
        $this->db->join('conversations c', 'c.conversation_id = m.conversation_id');
        $this->db->join('users u', 'u.id = c.student_id');
        $this->db->where('c.instructor_id', $instructor_id);
        $this->db->order_by('m.sent_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        
        log_message('debug', 'Get Recent Messages Query: ' . $this->db->last_query());
        return $query->result();
    }
}