<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Gudang_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Simple authentication (dalam production gunakan proper password hashing)
            if ($username === 'admin' && $password === 'admin123') {
                $user_data = [
                    'user_id' => 1,
                    'username' => $username,
                    'nama_lengkap' => 'Administrator',
                    'role' => 'admin',
                    'logged_in' => true
                ];

                $this->session->set_userdata($user_data);
                redirect('dashboard');
            } else {
                $data['error'] = 'Username atau password salah!';
            }
        }

        $this->load->view('auth/login', $data ?? null);
    }

    public function logout() {
        $this->session->unset_userdata(['user_id', 'username', 'nama_lengkap', 'role', 'logged_in']);
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
