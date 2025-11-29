<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scan extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // Load model jika diperlukan
        // $this->load->model('Scan_model');
        // $this->load->model('Barang_model');
        
        // Check login
        if(!$this->session->userdata('username')) {
            redirect('auth/login');
        }
    }
    
    public function index()
    {
        $data = array(
            'title' => 'Scan Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'scan',
            'content' => 'scan label/index'
        );
        
        $this->load->view('template', $data);
    }

    // API untuk memproses hasil scan
    public function process_scan()
    {
        $this->output->set_content_type('application/json');
        
        $label_code = $this->input->post('label_code');
        $scan_type = $this->input->post('scan_type'); // 'out' atau 'loading'
        
        if (empty($label_code)) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Kode label tidak boleh kosong'
            ]));
            return;
        }
        
        // Validasi kode label (dalam implementasi real, cek di database)
        $label_info = $this->validate_label($label_code);
        
        if (!$label_info['valid']) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => $label_info['message']
            ]));
            return;
        }
        
        // Proses scan berdasarkan type
        $result = $this->process_scan_type($label_code, $scan_type, $label_info);
        
        $this->output->set_output(json_encode($result));
    }

    // API untuk konfirmasi scan
    public function confirm_scan()
    {
        $this->output->set_content_type('application/json');
        
        $label_code = $this->input->post('label_code');
        $scan_type = $this->input->post('scan_type');
        $user_id = $this->session->userdata('user_id');
        
        // Simpan data scan ke database
        $scan_data = [
            'label_code' => $label_code,
            'scan_type' => $scan_type,
            'user_id' => $user_id,
            'scan_time' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ];
        
        // $this->Scan_model->save_scan($scan_data);
        
        // Update status barang jika diperlukan
        if ($scan_type === 'out') {
            // $this->Barang_model->update_status_out($label_code);
        } elseif ($scan_type === 'loading') {
            // $this->Barang_model->update_status_loading($label_code);
        }
        
        $this->output->set_output(json_encode([
            'success' => true,
            'message' => 'Scan berhasil dikonfirmasi',
            'data' => [
                'label_code' => $label_code,
                'scan_type' => $scan_type,
                'timestamp' => date('H:i')
            ]
        ]));
    }

    // API untuk mendapatkan statistik hari ini
    public function get_today_stats()
    {
        $this->output->set_content_type('application/json');
        
        $today = date('Y-m-d');
        
        // Dalam implementasi real, ambil dari database
        // $stats = $this->Scan_model->get_today_stats($today);
        
        // Data dummy untuk demo
        $stats = [
            'scanned' => rand(5, 15),
            'loaded' => rand(3, 10),
            'completed' => rand(2, 8)
        ];
        
        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $stats
        ]));
    }

    // API untuk mendapatkan scan terbaru
    public function get_recent_scans()
    {
        $this->output->set_content_type('application/json');
        
        // Dalam implementasi real, ambil dari database
        // $recent_scans = $this->Scan_model->get_recent_scans(5);
        
        // Data dummy untuk demo
        $recent_scans = [
            [
                'time' => '10:30',
                'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
                'type' => 'out'
            ],
            [
                'time' => '10:25',
                'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
                'type' => 'loading'
            ],
            [
                'time' => '10:15',
                'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
                'type' => 'out'
            ],
            [
                'time' => '10:10',
                'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
                'type' => 'loading'
            ],
            [
                'time' => '10:05',
                'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
                'type' => 'out'
            ]
        ];
        
        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $recent_scans
        ]));
    }

    // Validasi kode label
    private function validate_label($label_code)
    {
        // Dalam implementasi real, validasi dengan database
        // Contoh: cek apakah label exists, statusnya valid, dll.
        
        // Pattern validation
        if (!preg_match('/^LBL\d{3,}$/', $label_code)) {
            return [
                'valid' => false,
                'message' => 'Format kode label tidak valid'
            ];
        }
        
        // Simulasi cek database
        $valid_labels = ['LBL001', 'LBL002', 'LBL003', 'LBL004', 'LBL005', 'LBL006', 'LBL007', 'LBL008', 'LBL009', 'LBL010'];
        
        if (!in_array($label_code, $valid_labels)) {
            return [
                'valid' => false,
                'message' => 'Kode label tidak ditemukan'
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Label valid',
            'label_data' => [
                'code' => $label_code,
                'product_name' => 'Product for ' . $label_code,
                'status' => 'available'
            ]
        ];
    }

    // Proses scan berdasarkan type
    private function process_scan_type($label_code, $scan_type, $label_info)
    {
        // Dalam implementasi real, logika bisnis yang lebih kompleks
        $current_time = date('H:i');
        
        if ($scan_type === 'out') {
            return [
                'success' => true,
                'scan_type' => 'out',
                'message' => 'Scan berhasil - Barang keluar gudang',
                'data' => [
                    'label_code' => $label_code,
                    'type' => 'out',
                    'description' => 'Barang keluar gudang tercatat',
                    'timestamp' => $current_time,
                    'product_info' => $label_info['label_data']
                ]
            ];
        } elseif ($scan_type === 'loading') {
            return [
                'success' => true,
                'scan_type' => 'loading',
                'message' => 'Scan berhasil - Konfirmasi loading',
                'data' => [
                    'label_code' => $label_code,
                    'type' => 'loading',
                    'description' => 'Konfirmasi loading barang',
                    'timestamp' => $current_time,
                    'product_info' => $label_info['label_data']
                ]
            ];
        } else {
            // Auto-detect type based on label status
            $auto_type = (rand(0, 1) === 0) ? 'out' : 'loading';
            
            return $this->process_scan_type($label_code, $auto_type, $label_info);
        }
    }

    // API untuk mendapatkan info label
    public function get_label_info($label_code)
    {
        $this->output->set_content_type('application/json');
        
        $label_info = $this->validate_label($label_code);
        
        if (!$label_info['valid']) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => $label_info['message']
            ]));
            return;
        }
        
        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $label_info['label_data']
        ]));
    }
}