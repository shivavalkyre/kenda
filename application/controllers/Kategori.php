<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Kategori_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
        
        // Cek session login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // Kategori Barang
    public function index() {
        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        $status = $this->input->get('status') ?: 'all';
        $search = $this->input->get('search') ?: '';
        
        $offset = ($page - 1) * $limit;
        
        $params = [
            'status' => $status,
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search
        ];
        
        $kategori_list = $this->Kategori_model->get_kategori_list($params);
        $total_kategori = $this->Kategori_model->count_kategori($status, $search);
        $total_pages = ceil($total_kategori / $limit);
        
        $data = array(
            'title' => 'Kategori Barang - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'kategori',
            'content' => 'kategori/index',
            'kategori_list' => $kategori_list,
            'statistics' => $this->Kategori_model->get_kategori_statistics(),
            'current_page' => (int)$page,
            'total_pages' => $total_pages,
            'total_kategori' => $total_kategori,
            'limit' => $limit,
            'status_filter' => $status,
            'search' => $search
        );
        
        $this->load->view('template', $data);
    }

    // ==================== KATEGORI API METHODS ====================

    public function api_list_kategori() {
        try {
            // Get parameters from request
            $page = $this->input->get('page') ? intval($this->input->get('page')) : 1;
            $limit = $this->input->get('limit') ? intval($this->input->get('limit')) : 10;
            $status = $this->input->get('status') ? $this->input->get('status') : 'all';
            $search = $this->input->get('search') ?: '';
            
            // Validate parameters
            $page = max(1, $page);
            $limit = max(1, min(100, $limit)); // Limit max 100 items per page
            $offset = ($page - 1) * $limit;
            
            // Get total count with filter
            $total_items = $this->Kategori_model->count_kategori($status, $search);
            
            // Get paginated data
            $params = [
                'status' => $status,
                'limit' => $limit,
                'offset' => $offset,
                'search' => $search
            ];
            $data = $this->Kategori_model->get_kategori_list($params);
            
            // Calculate pagination info
            $total_pages = ceil($total_items / $limit);
            
            // Prepare response
            $response = [
                'success' => true,
                'data' => $data,
                'total_items' => $total_items,
                'total_pages' => $total_pages,
                'current_page' => $page,
                'limit' => $limit,
                'status_filter' => $status,
                'search' => $search
            ];
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
                
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]));
        }
    }

    public function api_kategori_statistics() {
        $statistics = $this->Kategori_model->get_kategori_statistics();
        
        $response = [
            'success' => true,
            'data' => $statistics
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_detail_kategori($id) {
        $kategori = $this->Kategori_model->get_kategori_detail($id);
        
        if ($kategori) {
            $response = [
                'success' => true,
                'data' => $kategori
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function simpan_kategori() {
        $post_data = $this->input->post();

        $data = [
            'kode_kategori' => $post_data['kode_kategori'],
            'nama_kategori' => $post_data['nama_kategori'],
            'deskripsi' => $post_data['deskripsi'],
            'status' => $post_data['status'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->Kategori_model->save_kategori($data);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Kategori berhasil disimpan'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal menyimpan kategori'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_kategori() {
        $post_data = $this->input->post();

        $data = [
            'kode_kategori' => $post_data['kode_kategori'],
            'nama_kategori' => $post_data['nama_kategori'],
            'deskripsi' => $post_data['deskripsi'],
            'status' => $post_data['status'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $id = $post_data['id'];

        $result = $this->Kategori_model->update_kategori($id, $data);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Kategori berhasil diupdate'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal mengupdate kategori'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function hapus_kategori($id) {
        $result = $this->Kategori_model->delete_kategori($id);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal menghapus kategori'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
