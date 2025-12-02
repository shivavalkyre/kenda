<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_model extends CI_Model {

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
        $this->db->where('status', 'aktif');
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
    
    /**
     * Get barang for export
     */
    public function get_barang_for_export() {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->where('status', 'aktif');
        $this->db->order_by('kategori', 'ASC');
        $this->db->order_by('nama_barang', 'ASC');
        return $this->db->get()->result_array();
    }

    // ==================== DASHBOARD METHODS ====================
    
    public function get_packing_pending() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('packing_list');
        $this->db->where('status_scan_out', 'printed');
        $this->db->where('status_scan_in', 'pending');
        $query = $this->db->get();
        return $query->row()->total ?: 0;
    }

    // ==================== PACKING LIST METHODS ====================

    public function get_total_packing() {
        return $this->db->count_all('packing_list');
    }

    public function get_pending_packing() {
        $this->db->where('status_scan_out', 'printed');
        $this->db->where('status_scan_in', 'pending');
        return $this->db->count_all_results('packing_list');
    }

    public function get_completed_packing() {
        $this->db->where('status_scan_in', 'completed');
        return $this->db->count_all_results('packing_list');
    }

    public function get_total_items_packed() {
        $this->db->select('SUM(jumlah_item) as total');
        $this->db->from('packing_list');
        $query = $this->db->get();
        return $query->row()->total ?: 0;
    }

    public function get_packing_list($limit = 10, $offset = 0, $filter = 'all', $search = '') {
        $this->db->select('*');
        $this->db->from('packing_list');
        
        if ($filter !== 'all') {
            switch($filter) {
                case 'draft':
                    $this->db->where('status_scan_out', 'draft');
                    break;
                case 'printed':
                    $this->db->where('status_scan_out', 'printed');
                    break;
                case 'scanned_out':
                    $this->db->where('status_scan_out', 'scanned_out');
                    break;
                case 'scanned_in':
                    $this->db->where('status_scan_in', 'scanned_in');
                    break;
                case 'completed':
                    $this->db->where('status_scan_in', 'completed');
                    break;
            }
        }
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('no_packing', $search);
            $this->db->or_like('customer', $search);
            $this->db->or_like('alamat', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
    }

    public function count_packing_list($filter = 'all', $search = '') {
        $this->db->from('packing_list');
        
        if ($filter !== 'all') {
            switch($filter) {
                case 'draft':
                    $this->db->where('status_scan_out', 'draft');
                    break;
                case 'printed':
                    $this->db->where('status_scan_out', 'printed');
                    break;
                case 'scanned_out':
                    $this->db->where('status_scan_out', 'scanned_out');
                    break;
                case 'scanned_in':
                    $this->db->where('status_scan_in', 'scanned_in');
                    break;
                case 'completed':
                    $this->db->where('status_scan_in', 'completed');
                    break;
            }
        }
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('no_packing', $search);
            $this->db->or_like('customer', $search);
            $this->db->or_like('alamat', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    public function get_packing_detail($id) {
        $this->db->where('id', $id);
        $packing = $this->db->get('packing_list')->row_array();
        
        if ($packing) {
            $this->db->select('pi.*, b.nama_barang, b.kategori, b.satuan');
            $this->db->from('packing_items pi');
            $this->db->join('barang b', 'pi.kode_barang = b.kode_barang', 'left');
            $this->db->where('pi.packing_id', $id);
            $packing['items'] = $this->db->get()->result_array();
            $packing['total_items'] = array_sum(array_column($packing['items'], 'qty'));
        }
        
        return $packing;
    }

    public function save_packing_list($data, $items) {
        $this->db->trans_start();
        
        if (empty($data['no_packing'])) {
            $data['no_packing'] = $this->generate_packing_number();
        }
        
        $total_items = array_sum(array_column($items, 'qty'));
        $data['jumlah_item'] = $total_items;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('packing_list', $data);
        $packing_id = $this->db->insert_id();
        
        foreach ($items as $item) {
            $item_data = [
                'packing_id' => $packing_id,
                'kode_barang' => $item['kode_barang'] ?? $item['kode'],
                'nama_barang' => $item['nama_barang'] ?? $item['nama'],
                'kategori' => $item['kategori'],
                'qty' => $item['qty'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('packing_items', $item_data);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $packing_id : false;
    }

    public function update_packing_list($packing_id, $data, $items) {
        $this->db->trans_start();
        
        $total_items = array_sum(array_column($items, 'qty'));
        $data['jumlah_item'] = $total_items;
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $packing_id);
        $this->db->update('packing_list', $data);
        
        $this->db->where('packing_id', $packing_id);
        $this->db->delete('packing_items');
        
        foreach ($items as $item) {
            $item_data = [
                'packing_id' => $packing_id,
                'kode_barang' => $item['kode_barang'] ?? $item['kode'],
                'nama_barang' => $item['nama_barang'] ?? $item['nama'],
                'kategori' => $item['kategori'],
                'qty' => $item['qty'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('packing_items', $item_data);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function delete_packing_list($packing_id) {
        $this->db->trans_start();
        
        $this->db->where('packing_id', $packing_id);
        $this->db->delete('packing_items');
        
        $this->db->where('id', $packing_id);
        $this->db->delete('packing_list');
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function update_scan_status($packing_id, $status, $time_field = null, $clear_time = false) {
        $data = [];
        
        if (strpos($status, 'scanned_out') !== false || $status === 'printed') {
            $data['status_scan_out'] = $status;
        } elseif (strpos($status, 'scanned_in') !== false || $status === 'completed') {
            $data['status_scan_in'] = $status;
        }
        
        if ($time_field && !$clear_time) {
            $data[$time_field] = date('Y-m-d H:i:s');
        } elseif ($time_field && $clear_time) {
            $data[$time_field] = null;
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $packing_id);
        return $this->db->update('packing_list', $data);
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
		
		// Filter berdasarkan status
		if ($params['status'] !== 'all') {
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
		
		$this->db->group_by('k.id');
		$this->db->order_by('k.nama_kategori', 'ASC');
		
		// Jika ada limit
		if ($params['limit'] !== null) {
			$this->db->limit($params['limit'], $params['offset']);
		}
		
		return $this->db->get()->result_array();
	}

	public function count_kategori($status = 'all', $search = null) {
		$this->db->from('kategori');
		
		// Filter berdasarkan status
		if ($status !== 'all') {
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
        
        $this->db->where('status', 'active');
        $active_kategori = $this->db->count_all_results('kategori');
        
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
            $this->db->like('kode_barang', $kategori['kode_kategori'], 'after');
            $this->db->where('status', 'aktif');
            $kategori['jumlah_barang'] = $this->db->count_all_results('barang');
            
            $this->db->select('kode_barang, nama_barang, stok, stok_minimum');
            $this->db->like('kode_barang', $kategori['kode_kategori'], 'after');
            $this->db->where('status', 'aktif');
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(5);
            $kategori['barang_terbaru'] = $this->db->get('barang')->result_array();
        }
        
        return $kategori;
    }

    public function save_kategori($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('kategori', $data);
    }

    public function update_kategori($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('kategori', $data);
    }

    public function delete_kategori($id) {
        $this->db->where('id', $id);
        return $this->db->delete('kategori');
    }

    // ==================== UTILITY METHODS ====================

    private function generate_packing_number() {
        $prefix = 'PL';
        $year = date('Y');
        $month = date('m');
        
        $this->db->select('no_packing');
        $this->db->like('no_packing', $prefix . $year . $month, 'after');
        $this->db->order_by('no_packing', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('packing_list')->row();
        
        if ($last) {
            $last_number = intval(substr($last->no_packing, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . $year . $month . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    // ==================== SCAN METHODS ====================

    public function get_packing_by_label($label_code) {
        $this->db->where('no_packing', $label_code);
        return $this->db->get('packing_list')->row_array();
    }

    public function get_today_scan_stats() {
        $today = date('Y-m-d');
        
        $this->db->where('DATE(scan_out_time)', $today);
        $this->db->where('status_scan_out', 'scanned_out');
        $scan_out_today = $this->db->count_all_results('packing_list');
        
        $this->db->where('DATE(scan_in_time)', $today);
        $this->db->where('status_scan_in', 'scanned_in');
        $scan_in_today = $this->db->count_all_results('packing_list');
        
        $this->db->where('DATE(scan_in_time)', $today);
        $this->db->where('status_scan_in', 'completed');
        $completed_today = $this->db->count_all_results('packing_list');
        
        return [
            'scan_out_today' => $scan_out_today,
            'scan_in_today' => $scan_in_today,
            'completed_today' => $completed_today
        ];
    }
}
