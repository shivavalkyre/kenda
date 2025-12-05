<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Scan extends CI_Controller {
    
//     public function __construct()
//     {
//         parent::__construct();
//         // Load model jika diperlukan
//         // $this->load->model('Scan_model');
//         // $this->load->model('Barang_model');
        
//         // Check login
//         if(!$this->session->userdata('username')) {
//             redirect('auth/login');
//         }
//     }
    
//     public function index()
//     {
//         $data = array(
//             'title' => 'Scan Label - KENDA',
//             'username' => $this->session->userdata('username'),
//             'active_menu' => 'scan',
//             'content' => 'scan label/index'
//         );
        
//         $this->load->view('template', $data);
//     }

//     // API untuk memproses hasil scan
//     public function process_scan()
//     {
//         $this->output->set_content_type('application/json');
        
//         $label_code = $this->input->post('label_code');
//         $scan_type = $this->input->post('scan_type'); // 'out' atau 'loading'
        
//         if (empty($label_code)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'Kode label tidak boleh kosong'
//             ]));
//             return;
//         }
        
//         // Validasi kode label (dalam implementasi real, cek di database)
//         $label_info = $this->validate_label($label_code);
        
//         if (!$label_info['valid']) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => $label_info['message']
//             ]));
//             return;
//         }
        
//         // Proses scan berdasarkan type
//         $result = $this->process_scan_type($label_code, $scan_type, $label_info);
        
//         $this->output->set_output(json_encode($result));
//     }

//     // API untuk konfirmasi scan
//     public function confirm_scan()
//     {
//         $this->output->set_content_type('application/json');
        
//         $label_code = $this->input->post('label_code');
//         $scan_type = $this->input->post('scan_type');
//         $user_id = $this->session->userdata('user_id');
        
//         // Simpan data scan ke database
//         $scan_data = [
//             'label_code' => $label_code,
//             'scan_type' => $scan_type,
//             'user_id' => $user_id,
//             'scan_time' => date('Y-m-d H:i:s'),
//             'status' => 'completed'
//         ];
        
//         // $this->Scan_model->save_scan($scan_data);
        
//         // Update status barang jika diperlukan
//         if ($scan_type === 'out') {
//             // $this->Barang_model->update_status_out($label_code);
//         } elseif ($scan_type === 'loading') {
//             // $this->Barang_model->update_status_loading($label_code);
//         }
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'message' => 'Scan berhasil dikonfirmasi',
//             'data' => [
//                 'label_code' => $label_code,
//                 'scan_type' => $scan_type,
//                 'timestamp' => date('H:i')
//             ]
//         ]));
//     }

//     // API untuk mendapatkan statistik hari ini
//     public function get_today_stats()
//     {
//         $this->output->set_content_type('application/json');
        
//         $today = date('Y-m-d');
        
//         // Dalam implementasi real, ambil dari database
//         // $stats = $this->Scan_model->get_today_stats($today);
        
//         // Data dummy untuk demo
//         $stats = [
//             'scanned' => rand(5, 15),
//             'loaded' => rand(3, 10),
//             'completed' => rand(2, 8)
//         ];
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => $stats
//         ]));
//     }

//     // API untuk mendapatkan scan terbaru
//     public function get_recent_scans()
//     {
//         $this->output->set_content_type('application/json');
        
//         // Dalam implementasi real, ambil dari database
//         // $recent_scans = $this->Scan_model->get_recent_scans(5);
        
//         // Data dummy untuk demo
//         $recent_scans = [
//             [
//                 'time' => '10:30',
//                 'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
//                 'type' => 'out'
//             ],
//             [
//                 'time' => '10:25',
//                 'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
//                 'type' => 'loading'
//             ],
//             [
//                 'time' => '10:15',
//                 'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
//                 'type' => 'out'
//             ],
//             [
//                 'time' => '10:10',
//                 'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
//                 'type' => 'loading'
//             ],
//             [
//                 'time' => '10:05',
//                 'label' => 'LBL' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
//                 'type' => 'out'
//             ]
//         ];
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => $recent_scans
//         ]));
//     }

//     // Validasi kode label
//     private function validate_label($label_code)
//     {
//         // Dalam implementasi real, validasi dengan database
//         // Contoh: cek apakah label exists, statusnya valid, dll.
        
//         // Pattern validation
//         if (!preg_match('/^LBL\d{3,}$/', $label_code)) {
//             return [
//                 'valid' => false,
//                 'message' => 'Format kode label tidak valid'
//             ];
//         }
        
//         // Simulasi cek database
//         $valid_labels = ['LBL001', 'LBL002', 'LBL003', 'LBL004', 'LBL005', 'LBL006', 'LBL007', 'LBL008', 'LBL009', 'LBL010'];
        
//         if (!in_array($label_code, $valid_labels)) {
//             return [
//                 'valid' => false,
//                 'message' => 'Kode label tidak ditemukan'
//             ];
//         }
        
//         return [
//             'valid' => true,
//             'message' => 'Label valid',
//             'label_data' => [
//                 'code' => $label_code,
//                 'product_name' => 'Product for ' . $label_code,
//                 'status' => 'available'
//             ]
//         ];
//     }

//     // Proses scan berdasarkan type
//     private function process_scan_type($label_code, $scan_type, $label_info)
//     {
//         // Dalam implementasi real, logika bisnis yang lebih kompleks
//         $current_time = date('H:i');
        
//         if ($scan_type === 'out') {
//             return [
//                 'success' => true,
//                 'scan_type' => 'out',
//                 'message' => 'Scan berhasil - Barang keluar gudang',
//                 'data' => [
//                     'label_code' => $label_code,
//                     'type' => 'out',
//                     'description' => 'Barang keluar gudang tercatat',
//                     'timestamp' => $current_time,
//                     'product_info' => $label_info['label_data']
//                 ]
//             ];
//         } elseif ($scan_type === 'loading') {
//             return [
//                 'success' => true,
//                 'scan_type' => 'loading',
//                 'message' => 'Scan berhasil - Konfirmasi loading',
//                 'data' => [
//                     'label_code' => $label_code,
//                     'type' => 'loading',
//                     'description' => 'Konfirmasi loading barang',
//                     'timestamp' => $current_time,
//                     'product_info' => $label_info['label_data']
//                 ]
//             ];
//         } else {
//             // Auto-detect type based on label status
//             $auto_type = (rand(0, 1) === 0) ? 'out' : 'loading';
            
//             return $this->process_scan_type($label_code, $auto_type, $label_info);
//         }
//     }

//     // API untuk mendapatkan info label
//     public function get_label_info($label_code)
//     {
//         $this->output->set_content_type('application/json');
        
//         $label_info = $this->validate_label($label_code);
        
//         if (!$label_info['valid']) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => $label_info['message']
//             ]));
//             return;
//         }
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => $label_info['label_data']
//         ]));
//     }
// }



class Scan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Scan_model');
        $this->load->model('Packing_model');
        $this->load->model('Barang_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Scan Label
    public function index() {
        $data = array(
            'title' => 'Scan Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'scan',
            'content' => 'scan label/index',
            'today_stats' => $this->Scan_model->get_today_scan_stats()
        );
        
        $this->load->view('template', $data);
    }

    // ==================== SCAN API METHODS ====================

    /**
     * API untuk Check Label
     */
    public function api_check_label($label_code) {
        if (empty($label_code)) {
            $response = [
                'success' => false,
                'message' => 'Kode label tidak valid'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Parse label code (format: LBL001 -> packing_id = 1)
        $packing_id = intval(str_replace('LBL', '', $label_code));
        
        if ($packing_id <= 0) {
            $response = [
                'success' => false,
                'message' => 'Format label tidak valid'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Get packing detail dengan validasi status
        $this->db->select('p.*, COUNT(pi.id) as jumlah_item');
        $this->db->from('packing_list p');
        $this->db->join('packing_items pi', 'p.id = pi.packing_id', 'left');
        $this->db->where('p.id', $packing_id);
        $this->db->where('p.status', 'active'); // Pastikan status aktif
        $this->db->group_by('p.id');
        
        $query = $this->db->get();
        $packing = $query->row_array();
        
        if ($packing) {
            // Format response untuk scan interface
            $response = [
                'success' => true,
                'data' => [
                    'id' => $packing['id'],
                    'no_packing' => $packing['no_packing'],
                    'customer' => $packing['customer'],
                    'status_scan_out' => $packing['status_scan_out'] ?? 'printed',
                    'status_scan_in' => $packing['status_scan_in'] ?? 'pending',
                    'scan_out_time' => $packing['scan_out_time'],
                    'scan_in_time' => $packing['scan_in_time'],
                    'total_items' => $packing['jumlah_item'] ?? 0
                ]
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Label tidak ditemukan'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Process Scan
     */
    public function api_process_scan() {
        $packing_id = $this->input->post('packing_id');
        $action = $this->input->post('action');
        $label_code = $this->input->post('label_code');

        if (empty($packing_id) || empty($action)) {
            $response = [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Validasi action sesuai dengan packing list system
        $valid_actions = ['scanned_out', 'scanned_in', 'completed'];
        if (!in_array($action, $valid_actions)) {
            $response = [
                'success' => false,
                'message' => 'Aksi tidak valid'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Get packing data untuk validasi
        $this->db->where('id', $packing_id);
        $query = $this->db->get('packing_list');
        $packing = $query->row_array();
        
        if (!$packing) {
            $response = [
                'success' => false,
                'message' => 'Packing list tidak ditemukan'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Validasi status transition sesuai packing list
        $validation_result = $this->_validate_scan_transition(
            $packing['status_scan_out'] ?? 'printed',
            $packing['status_scan_in'] ?? 'pending',
            $action
        );

        if (!$validation_result['valid']) {
            $response = [
                'success' => false,
                'message' => $validation_result['message']
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Process scan berdasarkan action
        $update_data = [];
        $time_field = '';
        
        switch ($action) {
            case 'scanned_out':
                $update_data['status_scan_out'] = 'scanned_out';
                $time_field = 'scan_out_time';
                break;
            case 'scanned_in':
                $update_data['status_scan_in'] = 'scanned_in';
                $time_field = 'scan_in_time';
                break;
            case 'completed':
                $update_data['status_scan_in'] = 'completed';
                $time_field = 'scan_in_time'; // atau completed_time jika ada
                break;
        }
        
        if ($time_field) {
            $update_data[$time_field] = date('Y-m-d H:i:s');
        }

        // Update packing status
        $this->db->where('id', $packing_id);
        $result = $this->db->update('packing_list', $update_data);

        if ($result) {
            // Log scan activity
            $log_data = [
                'packing_id' => $packing_id,
                'action' => $action,
                'label_code' => $label_code,
                'user_id' => $this->session->userdata('user_id') ?? 1,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('scan_logs', $log_data);

            $response = [
                'success' => true,
                'message' => 'Scan berhasil diproses',
                'data' => array_merge(['packing_id' => $packing_id], $update_data)
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal memproses scan'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Today Scan Statistics
     */
    public function api_today_scan_stats() {
        $stats = $this->Scan_model->get_today_scan_stats();
        
        $response = [
            'success' => true,
            'data' => $stats
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Recent Scans
     */
    public function api_recent_scans($limit = 5) {
        $scans = $this->Scan_model->get_recent_scans($limit);
        
        $response = [
            'success' => true,
            'data' => $scans
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // ==================== SCAN API METHODS LAMA (untuk kompatibilitas) ====================

    public function api_scan_out() {
        $packing_id = $this->input->post('packing_id');
        
        $result = $this->Scan_model->update_scan_status($packing_id, 'scanned_out', 'scan_out_time');

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Scan out berhasil'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal melakukan scan out'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_undo_scan_out() {
        $packing_id = $this->input->post('packing_id');
        
        $result = $this->Scan_model->update_scan_status($packing_id, 'printed', 'scan_out_time', true);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Undo scan out berhasil'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal undo scan out'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_scan_in() {
        $packing_id = $this->input->post('packing_id');
        
        $result = $this->Scan_model->update_scan_status($packing_id, 'scanned_in', 'scan_in_time');

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Scan in berhasil'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal melakukan scan in'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_complete_loading() {
        $packing_id = $this->input->post('packing_id');
        
        $result = $this->Scan_model->update_scan_status($packing_id, 'completed', 'scan_in_time');

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Loading selesai'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal menandai selesai loading'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // ==================== HELPER METHODS UNTUK SCAN ====================

    /**
     * Validasi Status Transition untuk Scan
     */
    private function _validate_scan_transition($current_out, $current_in, $target_action) {
        // Status constants sesuai dengan packing list
        $SCAN_STATUS = [
            'PRINTED' => 'printed',
            'SCANNED_OUT' => 'scanned_out',
            'SCANNED_IN' => 'scanned_in',
            'COMPLETED' => 'completed',
            'PENDING' => 'pending'
        ];

        // Validasi flow: printed → scanned_out → scanned_in → completed
        switch ($target_action) {
            case 'scanned_out':
                if ($current_out !== $SCAN_STATUS['PRINTED'] || $current_in !== $SCAN_STATUS['PENDING']) {
                    return [
                        'valid' => false,
                        'message' => 'Hanya bisa scan out dari status printed'
                    ];
                }
                break;
                
            case 'scanned_in':
                if ($current_out !== $SCAN_STATUS['SCANNED_OUT'] || $current_in !== $SCAN_STATUS['PENDING']) {
                    return [
                        'valid' => false,
                        'message' => 'Hanya bisa scan in setelah scan out'
                    ];
                }
                break;
                
            case 'completed':
                if ($current_in !== $SCAN_STATUS['SCANNED_IN']) {
                    return [
                        'valid' => false,
                        'message' => 'Hanya bisa complete setelah scan in'
                    ];
                }
                break;
        }

        return ['valid' => true, 'message' => 'OK'];
    }

    /**
     * Get Next Valid Actions untuk Scan Interface
     */
    private function _get_next_scan_actions($status_scan_out, $status_scan_in) {
        return [
            'canScanOut' => $status_scan_out === 'printed' && $status_scan_in === 'pending',
            'canUndoScanOut' => $status_scan_out === 'scanned_out' && $status_scan_in === 'pending',
            'canScanIn' => $status_scan_out === 'scanned_out' && $status_scan_in === 'pending',
            'canComplete' => $status_scan_out === 'scanned_out' && $status_scan_in === 'scanned_in',
            'canUndoScanIn' => $status_scan_in === 'scanned_in'
        ];
    }
}
