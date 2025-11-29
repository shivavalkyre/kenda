<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {
    
    public function index()
    {
        $data = array(
            'title' => 'Data Bearing - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'barang',
            'content' => 'barang/index'
        );
        
        $this->load->view('template', $data);
    }
}
