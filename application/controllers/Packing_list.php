<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packing_list extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Packing_model');
        $this->load->model('Barang_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Packing List
    public function index() {
        $data = array(
            'title' => 'Packing List - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'packing',
            'content' => 'packing list/index',
            'total_packing' => $this->Packing_model->get_total_packing(),
            'pending_packing' => $this->Packing_model->get_pending_packing(),
            'completed_packing' => $this->Packing_model->get_completed_packing(),
            'total_items' => $this->Packing_model->get_total_items_packed()
        );
        
        $this->load->view('template', $data);
    }

    // ==================== PACKING LIST API METHODS ====================

    public function api_list_packing() {
        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        $filter = $this->input->get('filter') ?: 'all';
        $search = $this->input->get('search') ?: '';

        $offset = ($page - 1) * $limit;

        $result = $this->Packing_model->get_packing_list($limit, $offset, $filter, $search);
        $total = $this->Packing_model->count_packing_list($filter, $search);

        $response = [
            'success' => true,
            'data' => $result,
            'pagination' => [
                'total' => $total,
                'page' => (int)$page,
                'limit' => (int)$limit,
                'total_pages' => ceil($total / $limit)
            ]
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_detail_packing($id) {
        $packing = $this->Packing_model->get_packing_detail($id);
        
        if ($packing) {
            $response = [
                'success' => true,
                'data' => $packing
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Packing list tidak ditemukan'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function simpan_packing() {
        $post_data = $this->input->post();

        $data = [
            'no_packing' => $post_data['no_packing'],
            'tanggal' => $post_data['tanggal'],
            'customer' => $post_data['customer'],
            'alamat' => $post_data['alamat'],
            'keterangan' => $post_data['keterangan'],
            'status_scan_out' => 'printed',
            'status_scan_in' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $items = json_decode($post_data['items'], true);

        $result = $this->Packing_model->save_packing_list($data, $items);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Packing list berhasil disimpan',
                'data' => ['packing_id' => $result]
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal menyimpan packing list'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_packing() {
        $post_data = $this->input->post();

        $data = [
            'no_packing' => $post_data['no_packing'],
            'tanggal' => $post_data['tanggal'],
            'customer' => $post_data['customer'],
            'alamat' => $post_data['alamat'],
            'keterangan' => $post_data['keterangan'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $items = json_decode($post_data['items'], true);
        $packing_id = $post_data['id'];

        $result = $this->Packing_model->update_packing_list($packing_id, $data, $items);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Packing list berhasil diupdate'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal mengupdate packing list'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_packing($id) {
        $result = $this->Packing_model->delete_packing_list($id);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Packing list berhasil dihapus'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal menghapus packing list'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // ==================== PACKING CETAK METHODS ====================

    /**
     * Halaman pilih format label
     */
    public function pilih_format($id) {
        $packing = $this->Packing_model->getPackingById($id);
        
        if (!$packing) {
            show_404();
        }
        
        $data = [
            'title' => 'Pilih Format Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'packing',
            'content' => 'packing list/pilih_format_label',
            'packing' => (array)$packing,
            'packing_id' => $id
        ];
        
        $this->load->view('template', $data);
    }

    /**
     * Cetak single label dengan barcode dan QR
     */
    public function cetak_label($id = null, $format = 'kenda') {
        // Ambil data packing list
        $packing = $this->Packing_model->getPackingById($id);
        
        if (!$packing) {
            show_404();
        }
        
        // Ambil items
        $packing->items = $this->Packing_model->getPackingItems($id);
        
        $data = [
            'packing' => $packing,
            'format' => $format,
            'print_time' => date('d/m/Y H:i:s'),
            'barcode_url' => $this->generate_barcode_url($packing->no_packing),
            'qr_url' => $this->generate_qr_url($packing)
        ];
        
        // Update status ke printed
        $this->Packing_model->updatePackingStatus($id, 'printed');
        
        // Load view berdasarkan format
        $this->load->view('packing list/cetak_label_' . $format, $data);
    }

    /**
     * Cetak multiple labels dengan barcode dan QR
     */
    public function cetak_label_multiple() {
        try {
            // Get IDs from query string
            $ids = $this->input->get('ids');
            $format = $this->input->get('format') ?: 'kenda';
            $autoprint = $this->input->get('autoprint') ?: 0;
            
            if (empty($ids)) {
                // Show empty state
                $data['packing_lists'] = [];
                $data['total_labels'] = 0;
                $data['error_message'] = 'Tidak ada ID packing list yang valid';
                $data['print_date'] = date('d/m/Y H:i:s');
                $data['autoprint'] = $autoprint;
                $this->load->view('packing list/cetak_label_multiple_kenda', $data);
                return;
            }
            
            // Convert string to array
            $ids_array = explode(',', $ids);
            $ids_array = array_map('intval', $ids_array);
            $ids_array = array_filter($ids_array);
            
            if (empty($ids_array)) {
                $data['packing_lists'] = [];
                $data['total_labels'] = 0;
                $data['error_message'] = 'Tidak ada ID packing list yang valid';
                $data['print_date'] = date('d/m/Y H:i:s');
                $data['autoprint'] = $autoprint;
                $this->load->view('packing list/cetak_label_multiple_kenda', $data);
                return;
            }
            
            // Get packing lists data
            $packing_lists = $this->Packing_model->get_packing_for_labels($ids_array);
            
            if (empty($packing_lists)) {
                $data['packing_lists'] = [];
                $data['total_labels'] = 0;
                $data['error_message'] = 'Data packing list tidak ditemukan';
                $data['print_date'] = date('d/m/Y H:i:s');
                $data['autoprint'] = $autoprint;
                $this->load->view('packing list/cetak_label_multiple_kenda', $data);
                return;
            }
            
            // Update status semua ke printed
            $this->Packing_model->update_batch_status($ids_array, 'printed');
            
            $data = [
                'packing_lists' => $packing_lists,
                'total_labels' => count($packing_lists),
                'error_message' => null,
                'print_date' => date('d/m/Y H:i:s'),
                'autoprint' => $autoprint,
                'format' => $format
            ];
            
            // Load view berdasarkan format
            $view_name = 'packing list/cetak_label_multiple_' . $format;
            $this->load->view($view_name, $data);
            
        } catch (Exception $e) {
            // Error handling
            $data['packing_lists'] = [];
            $data['total_labels'] = 0;
            $data['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $data['print_date'] = date('d/m/Y H:i:s');
            $data['autoprint'] = 0;
            
            $this->load->view('packing list/cetak_label_multiple_kenda', $data);
        }
    }

    /**
     * Cetak packing list (bukan label)
     */
    public function cetak($id) {
        // Ambil data dari model
        $packing = $this->Packing_model->get_packing_detail($id);
        
        if (!$packing) {
            show_404();
        }
        
        // Tambahkan barcode dan QR
        $packing['barcode_url'] = $this->generate_barcode_url($packing['no_packing']);
        $packing['qr_url'] = $this->generate_qr_url($packing);
        
        $data['packing'] = $packing;
        
        // Load view cetak
        $this->load->view('packing list/cetak_packing', $data);
    }

    /**
     * API untuk update status
     */
    public function api_update_status() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('packing_id', 'Packing ID', 'required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => validation_errors()
                ]));
            return;
        }
        
        $packing_id = $this->input->post('packing_id');
        $status = $this->input->post('status');
        
        $result = $this->Packing_model->updatePackingStatus($packing_id, $status);
        
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Status berhasil diupdate'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Gagal mengupdate status'
                ]));
        }
    }

    /**
     * API untuk update status batch
     */
    public function api_update_status_batch() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('packing_ids', 'Packing IDs', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => validation_errors()
                ]));
            return;
        }
        
        $packing_ids = json_decode($this->input->post('packing_ids'), true);
        $status = $this->input->post('status');
        
        if (!is_array($packing_ids) || empty($packing_ids)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Invalid packing IDs'
                ]));
            return;
        }
        
        $result = $this->Packing_model->update_batch_status($packing_ids, $status);
        
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Status berhasil diupdate untuk ' . count($packing_ids) . ' packing list'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Gagal mengupdate status batch'
                ]));
        }
    }

    /**
     * API untuk mendapatkan data barang
     */
    public function api_get_barang() {
        $this->load->model('Barang_model');
        
        $barang_list = $this->Barang_model->get_all_barang();
        
        $response = [
            'success' => true,
            'data' => $barang_list
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // ==================== BARCODE & QR HELPER METHODS ====================

    /**
     * Generate barcode URL
     */
    private function generate_barcode_url($text) {
        // Menggunakan Google Charts API untuk barcode
        // Atau gunakan library lokal jika ada
        $base_url = 'https://chart.googleapis.com/chart?';
        $params = [
            'cht' => 'bc', // barcode
            'chs' => '200x80', // size
            'chl' => $text, // data
            'chld' => 'H|10' // height and quiet zone
        ];
        
        return $base_url . http_build_query($params);
    }

    /**
     * Generate QR code URL
     */
    private function generate_qr_url($data) {
        $text = "Packing List: " . $data->no_packing . "\n";
        $text .= "Customer: " . $data->customer . "\n";
        $text .= "Date: " . date('d/m/Y', strtotime($data->tanggal)) . "\n";
        $text .= "Items: " . ($data->jumlah_item ?? 0);
        
        $base_url = 'https://chart.googleapis.com/chart?';
        $params = [
            'cht' => 'qr', // qr code
            'chs' => '150x150', // size
            'chl' => urlencode($text), // data
            'choe' => 'UTF-8' // encoding
        ];
        
        return $base_url . http_build_query($params);
    }

    // ==================== SCAN METHODS ====================

    public function api_scan_out() {
        $packing_id = $this->input->post('packing_id');
        
        $data = [
            'status_scan_out' => 'scanned_out',
            'scan_out_time' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $packing_id);
        $result = $this->db->update('packing_list', $data);
        
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Scan Out berhasil'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal melakukan Scan Out'
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_undo_scan_out() {
        $packing_id = $this->input->post('packing_id');
        
        $data = [
            'status_scan_out' => 'printed',
            'scan_out_time' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $packing_id);
        $result = $this->db->update('packing_list', $data);
        
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Undo Scan Out berhasil'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal undo Scan Out'
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_scan_in() {
        $packing_id = $this->input->post('packing_id');
        
        $data = [
            'status_scan_in' => 'scanned_in',
            'scan_in_time' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $packing_id);
        $result = $this->db->update('packing_list', $data);
        
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Scan In berhasil'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal melakukan Scan In'
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_complete_loading() {
        $packing_id = $this->input->post('packing_id');
        
        $data = [
            'status_scan_in' => 'completed',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $packing_id);
        $result = $this->db->update('packing_list', $data);
        
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
}
