<?php
class Lesson_model extends CI_Model {
    public function get_course_lessons($course_id) {
        return $this->db->get_where('lessons', ['course_id' => $course_id])->result();
    }
    
    public function add_lesson($data) {
        return $this->db->insert('lessons', $data);
    }
}