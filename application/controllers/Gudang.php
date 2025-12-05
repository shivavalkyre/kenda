<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Packing_model');
        $this->load->model('Dashboard_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Halaman redirect ke dashboard (untuk backward compatibility)
    public function index() {
        redirect('dashboard');
    }

    // Laporan Stok
    public function stok() {
        $data = array(
            'title' => 'Laporan Stok - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'stok',
            'content' => 'laporan stok/index',
            'total_barang' => $this->Barang_model->get_total_barang(),
            'total_tube' => $this->Barang_model->get_total_by_kategori('Tube'),
            'total_tire' => $this->Barang_model->get_total_by_kategori('Tire'),
            'stok_minimum' => $this->Barang_model->get_stok_minimum_count()
        );
        
        $this->load->view('template', $data);
    }

    // API untuk laporan stok
    public function api_laporan_stok($kategori = 'all') {
        if ($kategori === 'all') {
            $barang_list = $this->Barang_model->get_all_barang();
        } else {
            $barang_list = $this->Barang_model->get_barang_by_kategori($kategori);
        }
        
        $response = [
            'success' => true,
            'data' => $barang_list,
            'kategori' => $kategori,
            'total' => count($barang_list)
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // Export laporan stok
    public function export_laporan_stok($kategori = 'all') {
        $this->load->helper('download');
        
        if ($kategori === 'all') {
            $data = $this->Barang_model->get_all_barang();
            $filename = 'laporan_stok_semua_' . date('Y-m-d') . '.csv';
        } else {
            $data = $this->Barang_model->get_barang_by_kategori($kategori);
            $filename = 'laporan_stok_' . strtolower($kategori) . '_' . date('Y-m-d') . '.csv';
        }
        
        $csv = "Kode Barang,Nama Barang,Kategori,Stok,Satuan,Stok Minimum,Status\n";
        
        foreach ($data as $barang) {
            $csv .= '"' . $barang['kode_barang'] . '","' 
                    . $barang['nama_barang'] . '","' 
                    . $barang['kategori'] . '","' 
                    . $barang['stok'] . '","' 
                    . $barang['satuan'] . '","' 
                    . $barang['stok_minimum'] . '","' 
                    . $barang['status'] . '"' . "\n";
        }
        
        force_download($filename, $csv);
    }

    // API untuk statistik stok
    public function api_statistik_stok() {
        $statistics = [
            'total_barang' => $this->Barang_model->get_total_barang(),
            'total_tube' => $this->Barang_model->get_total_by_kategori('Tube'),
            'total_tire' => $this->Barang_model->get_total_by_kategori('Tire'),
            'stok_minimum' => $this->Barang_model->get_stok_minimum_count(),
            'stok_tube' => $this->Barang_model->get_total_by_kategori('Tube'),
            'stok_tire' => $this->Barang_model->get_total_by_kategori('Tire')
        ];
        
        $response = [
            'success' => true,
            'data' => $statistics
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
?>
