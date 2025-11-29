<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // Load model jika diperlukan
        // $this->load->model('Barang_model');
        
        // Check login (sesuaikan dengan auth system Anda)
        if(!$this->session->userdata('username')) {
            redirect('auth/login');
        }
    }
    
    public function index()
    {
        // Data statistik (dalam implementasi real, data ini akan diambil dari database)
        $data = array(
            'title' => 'Laporan Stok - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'laporan',
            'content' => 'laporan stok/index',
            'total_barang' => 1248,
            'total_tube' => 856,
            'total_tire' => 392,
            'stok_minimum' => 12,
            'stok_list' => [
                [
                    'kode' => 'TUB001',
                    'nama' => 'Tube Standard 17"',
                    'kategori' => 'Tube',
                    'stok_awal' => 300,
                    'masuk' => 50,
                    'keluar' => 0,
                    'stok_akhir' => 350,
                    'stok_minimum' => 50,
                    'status' => 'aman'
                ],
                [
                    'kode' => 'TIR001',
                    'nama' => 'Tire Radial 205/55/R16',
                    'kategori' => 'Tire',
                    'stok_awal' => 120,
                    'masuk' => 30,
                    'keluar' => 0,
                    'stok_akhir' => 150,
                    'stok_minimum' => 20,
                    'status' => 'aman'
                ],
                [
                    'kode' => 'TUB002',
                    'nama' => 'Tube Heavy Duty 19"',
                    'kategori' => 'Tube',
                    'stok_awal' => 20,
                    'masuk' => 0,
                    'keluar' => 12,
                    'stok_akhir' => 8,
                    'stok_minimum' => 15,
                    'status' => 'minimum'
                ],
                [
                    'kode' => 'TIR002',
                    'nama' => 'Tire Offroad 265/70/R16',
                    'kategori' => 'Tire',
                    'stok_awal' => 30,
                    'masuk' => 0,
                    'keluar' => 5,
                    'stok_akhir' => 25,
                    'stok_minimum' => 5,
                    'status' => 'aman'
                ],
                [
                    'kode' => 'TUB003',
                    'nama' => 'Tube Racing 15"',
                    'kategori' => 'Tube',
                    'stok_awal' => 50,
                    'masuk' => 0,
                    'keluar' => 5,
                    'stok_akhir' => 45,
                    'stok_minimum' => 10,
                    'status' => 'aman'
                ]
            ]
        );
        
        $this->load->view('template', $data);
    }

    // API untuk mendapatkan data stok berdasarkan filter (AJAX)
    public function get_data_stok()
    {
        // Set header sebagai JSON
        $this->output->set_content_type('application/json');
        
        // Get filter parameters
        $kategori = $this->input->get('kategori');
        $status = $this->input->get('status');
        $periode = $this->input->get('periode');
        
        // Sample data - dalam implementasi real, data ini diambil dari database
        $stok_list = [
            [
                'kode' => 'TUB001',
                'nama' => 'Tube Standard 17"',
                'kategori' => 'Tube',
                'stok_awal' => 300,
                'masuk' => 50,
                'keluar' => 0,
                'stok_akhir' => 350,
                'stok_minimum' => 50,
                'status' => 'aman'
            ],
            [
                'kode' => 'TIR001',
                'nama' => 'Tire Radial 205/55/R16',
                'kategori' => 'Tire',
                'stok_awal' => 120,
                'masuk' => 30,
                'keluar' => 0,
                'stok_akhir' => 150,
                'stok_minimum' => 20,
                'status' => 'aman'
            ],
            [
                'kode' => 'TUB002',
                'nama' => 'Tube Heavy Duty 19"',
                'kategori' => 'Tube',
                'stok_awal' => 20,
                'masuk' => 0,
                'keluar' => 12,
                'stok_akhir' => 8,
                'stok_minimum' => 15,
                'status' => 'minimum'
            ],
            [
                'kode' => 'TIR002',
                'nama' => 'Tire Offroad 265/70/R16',
                'kategori' => 'Tire',
                'stok_awal' => 30,
                'masuk' => 0,
                'keluar' => 5,
                'stok_akhir' => 25,
                'stok_minimum' => 5,
                'status' => 'aman'
            ],
            [
                'kode' => 'TUB003',
                'nama' => 'Tube Racing 15"',
                'kategori' => 'Tube',
                'stok_awal' => 50,
                'masuk' => 0,
                'keluar' => 5,
                'stok_akhir' => 45,
                'stok_minimum' => 10,
                'status' => 'aman'
            ]
        ];

        // Filter data berdasarkan parameter
        $filtered_data = $stok_list;
        
        if ($kategori && $kategori !== 'all') {
            $filtered_data = array_filter($filtered_data, function($item) use ($kategori) {
                return $item['kategori'] === $kategori;
            });
        }
        
        if ($status && $status !== 'all') {
            $filtered_data = array_filter($filtered_data, function($item) use ($status) {
                return $item['status'] === $status;
            });
        }

        // Return JSON response
        $this->output->set_output(json_encode([
            'success' => true,
            'data' => array_values($filtered_data),
            'total' => count($filtered_data)
        ]));
    }

    // API untuk mendapatkan data chart (AJAX)
    public function get_chart_data()
    {
        $this->output->set_content_type('application/json');
        
        $kategori = $this->input->get('kategori');
        
        // Sample chart data - dalam implementasi real, data ini diambil dari database
        $chart_data = [
            'categories' => ['Tube Standard', 'Tube Heavy', 'Tube Racing', 'Tire Radial', 'Tire Offroad'],
            'stok_akhir' => [350, 8, 45, 150, 25],
            'stok_minimum' => [50, 15, 10, 20, 5]
        ];

        // Filter berdasarkan kategori jika diperlukan
        if ($kategori && $kategori !== 'all') {
            if ($kategori === 'Tube') {
                $chart_data['categories'] = ['Tube Standard', 'Tube Heavy', 'Tube Racing'];
                $chart_data['stok_akhir'] = [350, 8, 45];
                $chart_data['stok_minimum'] = [50, 15, 10];
            } elseif ($kategori === 'Tire') {
                $chart_data['categories'] = ['Tire Radial', 'Tire Offroad'];
                $chart_data['stok_akhir'] = [150, 25];
                $chart_data['stok_minimum'] = [20, 5];
            }
        }

        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $chart_data
        ]));
    }

    // Get summary statistics (AJAX)
    public function get_summary()
    {
        $this->output->set_content_type('application/json');
        
        // Dalam implementasi real, data ini diambil dari database
        $summary = [
            'total_barang' => 1248,
            'total_tube' => 856,
            'total_tire' => 392,
            'stok_minimum' => 12
        ];

        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $summary
        ]));
    }

    // Export laporan stok
    public function export_stok()
    {
        // Get filter parameters
        $kategori = $this->input->get('kategori');
        $status = $this->input->get('status');
        $periode = $this->input->get('periode');
        
        // Load library untuk PDF
        $this->load->library('pdf');
        
        // Data untuk export
        $data = [
            'title' => 'Laporan Stok Barang',
            'kategori' => $kategori,
            'status' => $status,
            'periode' => $periode,
            'stok_list' => [] // Isi dengan data dari database
        ];
        
        // Generate PDF
        $html = $this->load->view('laporan/export_stok_pdf', $data, TRUE);
        $this->pdf->generate($html, 'laporan_stok_'.date('Y-m-d').'.pdf');
    }
}