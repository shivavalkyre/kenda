<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function login() {
        // Simple login form - you should implement proper authentication
        if($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // Simple authentication - replace with your actual authentication logic
            if($username == 'admin' && $password == 'admin123') {
                $this->session->set_userdata(array(
                    'logged_in' => true,
                    'username' => $username,
                    'user_id' => 1
                ));
                redirect('dashboard');
            } else {
                $data['error'] = 'Username atau password salah!';
            }
        }
        
        $this->load->view('auth/login');
    }

    public function logout() {
        $this->session->unset_userdata(array('logged_in', 'username', 'user_id'));
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
