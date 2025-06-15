<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_published_testimonials($limit = 2) {
        $this->db->select('
            t.id,
            t.student_name as name,
            t.message as content,
            t.rating,
            t.created_at,
            t.video_url,
            t.image_url
        ');
        $this->db->from('testimonials t');
        $this->db->where('t.status', 'published');
        $this->db->where('t.is_active', 1);
        $this->db->limit($limit);
        $this->db->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_published_testimonials_paginated($limit, $offset) {
        $this->db->select('
            t.id,
            t.student_name as name,
            t.message as content,
            t.rating,
            t.created_at,
            t.video_url,
            t.image_url
        ');
        $this->db->from('testimonials t');
        $this->db->where('t.status', 'published');
        $this->db->where('t.is_active', 1);
        $this->db->limit($limit, $offset);
        $this->db->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_total_testimonials() {
        $this->db->where('status', 'published');
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('testimonials');
    }

    public function insert_testimonial($data) {
        return $this->db->insert('testimonials', $data);
    }
}