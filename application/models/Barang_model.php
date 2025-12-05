<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ==================== BARANG METHODS ====================
    
    /**
     * Get all barang
     */
    public function get_all_barang() {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Get all barang including nonaktif
     */
    public function get_all_barang_with_nonaktif() {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Get barang by kategori
     */
    public function get_barang_by_kategori($kategori) {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->where('kategori', $kategori);
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Get barang detail by kode_barang
     */
    public function get_barang_detail($kode_barang) {
        $this->db->where('kode_barang', $kode_barang);
        $query = $this->db->get('barang');
        return $query->row_array();
    }
    
    /**
     * Get barang by ID
     */
    public function get_barang_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('barang');
        return $query->row_array();
    }
    
    /**
     * Check if kode barang exists
     */
    public function is_kode_barang_exist($kode_barang, $exclude_id = null) {
        $this->db->where('kode_barang', $kode_barang);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('barang') > 0;
    }
    
    /**
     * Get total barang count
     */
    public function get_total_barang() {
        $this->db->where('status', 'aktif');
        return $this->db->count_all_results('barang');
    }
    
    /**
     * Get total barang count including nonaktif
     */
    public function get_total_barang_all() {
        return $this->db->count_all_results('barang');
    }
    
    /**
     * Get total by kategori
     */
    public function get_total_by_kategori($kategori) {
        $this->db->where('kategori', $kategori);
        $this->db->where('status', 'aktif');
        return $this->db->count_all_results('barang');
    }
    
    /**
     * Get stok minimum count
     */
    public function get_stok_minimum_count() {
        $this->db->where('stok <= stok_minimum');
        $this->db->where('status', 'aktif');
        return $this->db->count_all_results('barang');
    }
    
    /**
     * Add new barang
     */
    public function tambah_barang($data) {
        return $this->db->insert('barang', $data);
    }
    
    /**
     * Update barang
     */
    public function update_barang($kode_barang, $data) {
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->update('barang', $data);
    }
    
    /**
     * Delete barang (soft delete)
     */
    public function hapus_barang($kode_barang) {
        $data = [
            'status' => 'nonaktif',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->update('barang', $data);
    }
    
    /**
     * Update stok barang
     */
    public function update_stok_barang($kode_barang, $stok_baru) {
        $data = [
            'stok' => $stok_baru,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('kode_barang', $kode_barang);
        return $this->db->update('barang', $data);
    }
    
    /**
     * Add stok log
     */
    public function add_stok_log($data) {
        return $this->db->insert('log_stok', $data);
    }
    
    /**
     * Get stok logs for barang
     */
    public function get_stok_logs($kode_barang, $limit = 10) {
        $this->db->where('kode_barang', $kode_barang);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('log_stok')->result_array();
    }
    
    /**
     * Get barang for dropdown/autocomplete
     */
    public function get_barang_for_dropdown() {
        $this->db->select('kode_barang, nama_barang, kategori, satuan, stok');
        $this->db->from('barang');
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    
    /**
     * Get barang with pagination
     */
    public function get_barang_paginated($limit, $offset, $search = '', $filter = 'all') {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->where('status', 'aktif');
        
        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_barang', $search);
            $this->db->or_like('nama_barang', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }
        
        // Apply filter
        if ($filter !== 'all') {
            switch($filter) {
                case 'tube':
                    $this->db->where('kategori', 'Tube');
                    break;
                case 'tire':
                    $this->db->where('kategori', 'Tire');
                    break;
                case 'stok-minimum':
                    $this->db->where('stok <= stok_minimum');
                    break;
                case 'nonaktif':
                    $this->db->where('status', 'nonaktif');
                    break;
                case 'aktif':
                    $this->db->where('status', 'aktif');
                    break;
            }
        }
        
        $this->db->order_by('nama_barang', 'ASC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
    }
    
    /**
     * Get barang with pagination including nonaktif
     */
    public function get_barang_paginated_all($limit, $offset, $search = '', $filter = 'all') {
        $this->db->select('*');
        $this->db->from('barang');
        
        // Tidak ada filter status, tampilkan semua (aktif dan nonaktif)
        
        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_barang', $search);
            $this->db->or_like('nama_barang', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }
        
        // Apply filter
        if ($filter !== 'all') {
            switch($filter) {
                case 'tube':
                    $this->db->where('kategori', 'Tube');
                    break;
                case 'tire':
                    $this->db->where('kategori', 'Tire');
                    break;
                case 'stok-minimum':
                    $this->db->where('stok <= stok_minimum');
                    break;
                case 'nonaktif':
                    $this->db->where('status', 'nonaktif');
                    break;
                case 'aktif':
                    $this->db->where('status', 'aktif');
                    break;
            }
        }
        
        $this->db->order_by('nama_barang', 'ASC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
    }
    
    /**
     * Count barang with filters
     */
    public function count_barang($search = '', $filter = 'all') {
        $this->db->from('barang');
        $this->db->where('status', 'aktif');
        
        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_barang', $search);
            $this->db->or_like('nama_barang', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }
        
        // Apply filter
        if ($filter !== 'all') {
            switch($filter) {
                case 'tube':
                    $this->db->where('kategori', 'Tube');
                    break;
                case 'tire':
                    $this->db->where('kategori', 'Tire');
                    break;
                case 'stok-minimum':
                    $this->db->where('stok <= stok_minimum');
                    break;
                case 'nonaktif':
                    $this->db->where('status', 'nonaktif');
                    break;
                case 'aktif':
                    $this->db->where('status', 'aktif');
                    break;
            }
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Count barang with filters including nonaktif
     */
    public function count_barang_all($search = '', $filter = 'all') {
        $this->db->from('barang');
        
        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_barang', $search);
            $this->db->or_like('nama_barang', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }
        
        // Apply filter
        if ($filter !== 'all') {
            switch($filter) {
                case 'tube':
                    $this->db->where('kategori', 'Tube');
                    break;
                case 'tire':
                    $this->db->where('kategori', 'Tire');
                    break;
                case 'stok-minimum':
                    $this->db->where('stok <= stok_minimum');
                    break;
                case 'nonaktif':
                    $this->db->where('status', 'nonaktif');
                    break;
                case 'aktif':
                    $this->db->where('status', 'aktif');
                    break;
            }
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Get barang statistics
     */
		public function get_barang_statistics() {
			$total_barang = $this->get_total_barang();
			$total_tube = $this->get_total_by_kategori('Tube');
			$total_tire = $this->get_total_by_kategori('Tire');
			$stok_minimum = $this->get_stok_minimum_count();
			
			return [
				'total_barang' => $total_barang,
				'total_tube' => $total_tube,
				'total_tire' => $total_tire,
				'stok_minimum' => $stok_minimum
			];
		}

		public function get_barang_export_data($include_nonaktif = false) {
		$this->db->select('kode_barang, nama_barang, kategori, stok, satuan, stok_minimum, status, deskripsi');
		$this->db->from('barang');
		
		if (!$include_nonaktif) {
			$this->db->where('status', 'aktif');
		}
		
		$this->db->order_by('kategori', 'ASC');
		$this->db->order_by('kode_barang', 'ASC');
		
		return $this->db->get()->result_array();
	}
    
	public function get_all_barang_for_export() {
		$this->db->select('kode_barang, nama_barang, kategori, stok, satuan, stok_minimum, status, deskripsi');
		$this->db->from('barang');
		$this->db->order_by('kode_barang', 'ASC');
		return $this->db->get()->result_array();
	}

	public function get_barang_for_export() {
		$this->db->select('kode_barang, nama_barang, kategori, stok, satuan, stok_minimum, status, deskripsi');
		$this->db->from('barang');
		$this->db->where('status', 'aktif');
		$this->db->order_by('kode_barang', 'ASC');
		return $this->db->get()->result_array();
	}
}
?>
