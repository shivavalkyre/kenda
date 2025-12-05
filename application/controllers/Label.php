<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Label extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Label_model');
        $this->load->model('Packing_model');
        $this->load->model('Barang_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Halaman utama label management
    public function index() {
        $data = array(
            'title' => 'Management Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'label',
            'content' => 'label/index'
        );
        
        $this->load->view('template', $data);
    }

    // ==================== LABEL GENERATION & PRINTING ====================

    /**
     * Halaman untuk memilih format label
     */
    public function pilih_format_label($packing_id) {
        $data = [
            'title' => 'Pilih Format Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'packing',
            'content' => 'packing list/pilih_format_label',
            'packing_id' => $packing_id,
            'packing' => $this->Packing_model->get_packing_detail($packing_id)
        ];
        
        $this->load->view('template', $data);
    }

    /**
     * API untuk generate label dengan format
     */
    public function api_generate_label_format() {
        // Set response header sebagai JSON
        $this->output->set_content_type('application/json');
        
        try {
            // Log untuk debugging
            log_message('debug', 'API Generate Format Called');
            
            // Get POST data
            $packing_id = $this->input->post('packing_id');
            $label_count = $this->input->post('label_count') ?: 1;
            $label_format = $this->input->post('label_format') ?: 'kenda';
            $label_type = $this->input->post('label_type') ?: 'single';
            
            // Validasi input
            if (empty($packing_id)) {
                throw new Exception('Packing ID diperlukan');
            }
            
            $packing_id = (int)$packing_id;
            $label_count = (int)$label_count;
            
            // Validasi packing exists
            $this->db->where('id', $packing_id);
            $packing = $this->db->get('packing_list')->row();
            
            if (!$packing) {
                throw new Exception("Packing list #{$packing_id} tidak ditemukan");
            }
            
            // Validasi format
            $valid_formats = ['kenda', 'xds', 'btg', 'standard'];
            if (!in_array($label_format, $valid_formats)) {
                throw new Exception('Format label tidak valid. Pilih: kenda, xds, btg, standard');
            }
            
            // Generate labels menggunakan model
            $labels = $this->Label_model->generate_labels_with_format(
                $packing_id, 
                $label_count, 
                $label_format, 
                $label_type
            );
            
            if ($labels === false || empty($labels)) {
                throw new Exception('Gagal generate label. Tidak ada label yang dibuat.');
            }
            
            // Response success
            $response = [
                'success' => true,
                'message' => 'Label berhasil digenerate',
                'data' => [
                    'labels' => $labels,
                    'total_labels' => count($labels),
                    'packing_id' => $packing_id,
                    'format' => $label_format
                ]
            ];
            
        } catch (Exception $e) {
            // Log error
            log_message('error', 'API Generate Label Error: ' . $e->getMessage());
            
            $response = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
        
        // Output JSON
        echo json_encode($response);
        return;
    }

    /**
     * Cetak label dengan format tertentu
     */
    public function cetak_label_format($label_id, $format = 'kenda') {
        // Update label status menjadi printed
        $this->Label_model->update_label_printed($label_id);
        
        $label_data = $this->Label_model->get_label_data_for_print($label_id, $format);
        
        if (!$label_data) {
            show_404();
        }
        
        $data = [
            'label_data' => $label_data,
            'print_time' => date('Y-m-d H:i:s')
        ];
        
        // Load view berdasarkan format
        $view_name = "packing list/cetak_label_{$format}";
        $this->load->view($view_name, $data);
    }

    /**
     * View untuk melihat semua label yang sudah digenerate
     */
    public function view_packing_labels($packing_id) {
        $data = [
            'title' => 'Daftar Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'packing',
            'content' => 'packing list/view_packing_labels',
            'packing_id' => $packing_id,
            'packing' => $this->Packing_model->get_packing_detail($packing_id),
            'labels' => $this->Label_model->get_labels_by_packing_id($packing_id)
        ];
        
        $this->load->view('template', $data);
    }

    // ==================== LABEL API METHODS ====================

    /**
     * API untuk check label (single dan multi)
     */
    public function api_check_label_detail($label_code) {
        $label = $this->Label_model->get_label_by_code($label_code);
        
        if ($label) {
            // Get packing details
            $packing = $this->Packing_model->get_packing_detail($label['packing_id']);
            
            // Get all labels for this packing
            $all_labels = $this->Label_model->get_labels_by_packing($label['packing_id']);
            
            // Determine next valid actions
            $next_actions = $this->_get_next_label_actions($label['status'], $label['label_type']);
            
            $response = [
                'success' => true,
                'data' => [
                    'label' => $label,
                    'packing' => $packing,
                    'all_labels' => $all_labels,
                    'next_actions' => $next_actions,
                    'is_multi_label' => $label['label_type'] === 'master' || $label['label_type'] === 'child'
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
     * API untuk process label scan (single dan multi)
     */
    public function api_process_label_scan() {
        $label_id = $this->input->post('label_id');
        $label_code = $this->input->post('label_code');
        $action = $this->input->post('action');
        $user_id = $this->session->userdata('user_id');
        
        if (empty($label_id) && !empty($label_code)) {
            // Get label ID from code
            $this->db->select('id');
            $this->db->where('label_code', $label_code);
            $label = $this->db->get('labels')->row_array();
            $label_id = $label['id'] ?? null;
        }
        
        if (empty($label_id) || empty($action)) {
            $response = [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        $result = $this->Label_model->process_label_scan($label_id, $action, $user_id);
        
        if ($result) {
            // Get updated label info
            $label = $this->Label_model->get_label_by_code($label_code);
            
            $response = [
                'success' => true,
                'message' => 'Scan berhasil diproses',
                'data' => [
                    'label_id' => $label_id,
                    'label_code' => $label_code,
                    'action' => $action,
                    'new_status' => $label['status'] ?? $action,
                    'timestamp' => date('Y-m-d H:i:s')
                ]
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
     * API untuk batch scan (multiple labels)
     */
    public function api_batch_scan_labels() {
        $label_codes = $this->input->post('label_codes');
        $action = $this->input->post('action');
        $user_id = $this->session->userdata('user_id');
        
        if (empty($label_codes) || !is_array($label_codes) || empty($action)) {
            $response = [
                'success' => false,
                'message' => 'Data tidak lengkap'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        $results = [];
        $success_count = 0;
        $failed_count = 0;
        
        foreach ($label_codes as $label_code) {
            $this->db->select('id');
            $this->db->where('label_code', $label_code);
            $label = $this->db->get('labels')->row_array();
            
            if ($label) {
                $result = $this->Label_model->process_label_scan($label['id'], $action, $user_id);
                
                if ($result) {
                    $success_count++;
                    $results[] = [
                        'label_code' => $label_code,
                        'status' => 'success'
                    ];
                } else {
                    $failed_count++;
                    $results[] = [
                        'label_code' => $label_code,
                        'status' => 'failed'
                    ];
                }
            } else {
                $failed_count++;
                $results[] = [
                    'label_code' => $label_code,
                    'status' => 'not_found'
                ];
            }
        }
        
        $response = [
            'success' => $success_count > 0,
            'message' => "Diproses: {$success_count} sukses, {$failed_count} gagal",
            'data' => [
                'total' => count($label_codes),
                'success' => $success_count,
                'failed' => $failed_count,
                'results' => $results
            ]
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk get label statistics
     */
    public function api_label_statistics() {
        $date = $this->input->get('date') ?: date('Y-m-d');
        
        $stats = $this->Label_model->get_label_statistics($date);
        
        $response = [
            'success' => true,
            'data' => $stats
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk cetak label (single dan multiple)
     */
    public function api_cetak_label_batch() {
        $label_ids = $this->input->post('label_ids');
        $packing_id = $this->input->post('packing_id');
        
        if (empty($label_ids) && empty($packing_id)) {
            $response = [
                'success' => false,
                'message' => 'Tidak ada label untuk dicetak'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Jika packing_id diberikan, get semua labels untuk packing tersebut
        if ($packing_id && empty($label_ids)) {
            $labels = $this->Label_model->get_labels_by_packing($packing_id);
            $label_ids = array_column($labels, 'id');
        }
        
        // Update label status to printed
        $this->db->where_in('id', $label_ids);
        $this->db->update('labels', [
            'status' => 'printed',
            'printed_at' => date('Y-m-d H:i:s')
        ]);
        
        // Get label data for printing
        $this->db->where_in('id', $label_ids);
        $labels_data = $this->db->get('labels')->result_array();
        
        $response = [
            'success' => true,
            'message' => 'Label siap dicetak',
            'data' => [
                'labels' => $labels_data,
                'total' => count($labels_data),
                'print_time' => date('Y-m-d H:i:s')
            ]
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get next valid actions for label
     */
    private function _get_next_label_actions($current_status, $label_type) {
        $actions = [
            'canPrint' => $current_status === 'active',
            'canScanOut' => in_array($current_status, ['active', 'printed']),
            'canScanIn' => $current_status === 'scanned_out',
            'canComplete' => $current_status === 'scanned_in',
            'canVoid' => $current_status !== 'void'
        ];
        
        // Untuk master label, hanya bisa scan jika semua child sudah ready
        if ($label_type === 'master') {
            $actions['canScanOut'] = false;
            $actions['canScanIn'] = false;
            $actions['canComplete'] = false;
        }
        
        return $actions;
    }
}
?>
