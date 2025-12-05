<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Login page
    public function login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $data = array(
            'title' => 'Login - KENDA Warehouse System'
        );
        
        $this->load->view('auth/login', $data);
    }

    // Process login
    public function process_login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/login');
        }
        
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        // Contoh authentication sederhana
        // Dalam implementasi real, gunakan database dan hashing password
        $valid_users = [
            'admin' => 'admin123',
            'user' => 'user123'
        ];
        
        // Cek username dan password
        if (isset($valid_users[$username]) && $valid_users[$username] === $password) {
            // Set session data
            $session_data = [
                'user_id' => ($username === 'admin') ? 1 : 2,
                'username' => $username,
                'logged_in' => TRUE,
                'role' => ($username === 'admin') ? 'admin' : 'user'
            ];
            
            $this->session->set_userdata($session_data);
            
            // Redirect ke dashboard
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah');
            redirect('auth/login');
        }
    }

    // Logout
    public function logout() {
        $this->session->unset_userdata(['user_id', 'username', 'logged_in', 'role']);
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    // Check session - untuk digunakan di controller lain
    public function check_session() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
}
