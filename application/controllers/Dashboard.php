<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Dashboard extends CI_Controller {
    
//     public function index()
//     {
//         $data = array(
//             'title' => 'Dashboard - KENDA',
//             'username' => $this->session->userdata('username'),
//             'active_menu' => 'dashboard',
//             'content' => 'dashboard/index'
//         );
        
//         $this->load->view('template', $data);
//     }
// }



class Dashboard extends CI_Controller {
    
	 public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Barang_model');
        $this->load->model('Packing_model');
        $this->load->model('Scan_model');
        $this->load->library('session');
        $this->load->helper('url');
        
        // Cek session untuk autentikasi
        if (!$this->session->userdata('username')) {
            redirect('auth/login');
        }
    }

    public function index() {
        // Ambil semua data statistik
        $dashboard_stats = $this->Dashboard_model->get_dashboard_stats();
        
        $data = array(
            'title' => 'Dashboard - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'dashboard',
            'content' => 'dashboard/index',
            'total_barang' => $this->Barang_model->get_total_barang(),
            'total_tube' => $this->Barang_model->get_total_by_kategori('Tube'),
            'total_tire' => $this->Barang_model->get_total_by_kategori('Tire'),
            'packing_pending' => $this->Packing_model->get_pending_packing(),
            'stok_minimum' => $this->Barang_model->get_stok_minimum_count(),
            
            // Data untuk dashboard
            'dashboard_stats' => $dashboard_stats,
            'recent_activities' => $this->Dashboard_model->get_recent_activities(5),
            'recent_packing' => $this->Dashboard_model->get_recent_packing(5),
            'monthly_stats' => $this->Dashboard_model->get_monthly_stats(date('m'), date('Y')),
            'tube_stats' => $this->Dashboard_model->get_category_stats('Tube'),
            'tire_stats' => $this->Dashboard_model->get_category_stats('Tire'),
            'stok_comparison' => $this->Dashboard_model->get_stok_comparison_data()
        );
        
        $this->load->view('template', $data);
    }
    // API untuk dashboard stats
    public function api_dashboard_stats() {
        $stats = $this->Dashboard_model->get_dashboard_stats();
        
        $response = [
            'success' => true,
            'data' => $stats
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // API untuk recent activities
    public function api_recent_activities($limit = 5) {
        $activities = $this->Dashboard_model->get_recent_activities($limit);
        
        $response = [
            'success' => true,
            'data' => $activities
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // API untuk monthly stats
    public function api_monthly_stats($month = null, $year = null) {
        if (!$month) $month = date('m');
        if (!$year) $year = date('Y');
        
        $stats = $this->Dashboard_model->get_monthly_stats($month, $year);
        
        $response = [
            'success' => true,
            'data' => $stats,
            'month' => $month,
            'year' => $year
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
