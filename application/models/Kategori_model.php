<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ==================== KATEGORI METHODS ====================

    public function get_kategori_list($params = []) {
        $defaults = [
            'status' => 'all',
            'limit' => null,
            'offset' => 0,
            'search' => null
        ];
        
        $params = array_merge($defaults, $params);
        
        $this->db->select('k.*, COUNT(b.id) as jumlah_barang');
        $this->db->from('kategori k');
        $this->db->join('barang b', 'k.kode_kategori = SUBSTRING(b.kode_barang, 1, 3)', 'left');
        $this->db->group_by('k.id');
        
        // Filter berdasarkan status
        if ($params['status'] !== 'all') {
            // Database menggunakan 'active' dan 'inactive', jadi tidak perlu konversi
            $this->db->where('k.status', $params['status']);
        }
        
        // Filter berdasarkan pencarian
        if (!empty($params['search'])) {
            $this->db->group_start();
            $this->db->like('k.kode_kategori', $params['search']);
            $this->db->or_like('k.nama_kategori', $params['search']);
            $this->db->or_like('k.deskripsi', $params['search']);
            $this->db->group_end();
        }
        
        $this->db->order_by('k.nama_kategori', 'ASC');
        
        // Jika ada limit
        if ($params['limit'] !== null) {
            $this->db->limit($params['limit'], $params['offset']);
        }
        
        $result = $this->db->get()->result_array();
        
        // Tidak perlu konversi status karena database sudah menggunakan 'active'/'inactive'
        // Frontend juga menggunakan 'active'/'inactive'
        return $result;
    }

    public function count_kategori($status = 'all', $search = null) {
        $this->db->from('kategori');
        
        // Filter berdasarkan status
        if ($status !== 'all') {
            // Database menggunakan 'active' dan 'inactive'
            $this->db->where('status', $status);
        }
        
        // Filter berdasarkan pencarian
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_kategori', $search);
            $this->db->or_like('nama_kategori', $search);
            $this->db->or_like('deskripsi', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    public function get_kategori_statistics() {
        $total_kategori = $this->db->count_all('kategori');
        
        // Database menggunakan 'active' bukan 'aktif'
        $this->db->where('status', 'active');
        $active_kategori = $this->db->count_all_results('kategori');
        
        // Database menggunakan 'inactive' bukan 'nonaktif'
        $this->db->where('status', 'inactive');
        $inactive_kategori = $this->db->count_all_results('kategori');
        
        $this->db->where('status', 'aktif');
        $total_barang = $this->db->count_all_results('barang');
        
        return [
            'total_kategori' => $total_kategori,
            'active_kategori' => $active_kategori,
            'inactive_kategori' => $inactive_kategori,
            'total_barang' => $total_barang
        ];
    }

    public function get_kategori_detail($id) {
        $this->db->where('id', $id);
        $kategori = $this->db->get('kategori')->row_array();
        
        if ($kategori) {
            // Database menggunakan 'active'/'inactive', frontend juga 'active'/'inactive'
            // Jadi tidak perlu konversi
            
            $this->db->like('kode_barang', $kategori['kode_kategori'], 'after');
            $this->db->where('status', 'aktif');
            $kategori['jumlah_barang'] = $this->db->count_all_results('barang');
            
            $this->db->select('kode_barang, nama_barang, stok, stok_minimum, status');
            $this->db->like('kode_barang', $kategori['kode_kategori'], 'after');
            $this->db->where('status', 'aktif');
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(5);
            $kategori['barang_terbaru'] = $this->db->get('barang')->result_array();
        }
        
        return $kategori;
    }

    public function save_kategori($data) {
        // Database menggunakan 'active'/'inactive', frontend juga mengirim 'active'/'inactive'
        // Jadi tidak perlu konversi
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('kategori', $data);
    }

    public function update_kategori($id, $data) {
        // Database menggunakan 'active'/'inactive', frontend juga mengirim 'active'/'inactive'
        // Jadi tidak perlu konversi
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('kategori', $data);
    }

    public function delete_kategori($id) {
        $this->db->where('id', $id);
        return $this->db->delete('kategori');
    }
}
