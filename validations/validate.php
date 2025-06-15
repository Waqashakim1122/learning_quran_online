<?php

class Validate extends CI_Controller {

        public function index()
        {
                $this->load->helper(array('form', 'url'));

                $this->load->library('form_validation');

                #Setting the rules
                $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
                $this->form_validation->set_rules('password', 'Password', 'required|max_length[6]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

                if ($this->form_validation->run() == FALSE)
                {
                        $this->load->view('validations/validation_form.php');
                }
                else
                {       
                        $data = $this->input->post();
                        echo "<pre>";
                        print_r($data);
                        exit();
                        $this->load->view('validations/success');
                }
        }
}