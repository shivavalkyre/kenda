<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ==================== DASHBOARD METHODS ====================
    
    public function get_total_barang() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('barang');
        $this->db->where('status', 'aktif');
        $query = $this->db->get();
        return $query->row()->total ?: 0;
    }

    public function get_total_by_kategori($kategori) {
        $this->db->select('SUM(stok) as total');
        $this->db->from('barang');
        $this->db->where('kategori', $kategori);
        $this->db->where('status', 'aktif');
        $query = $this->db->get();
        return $query->row()->total ?: 0;
    }

    public function get_packing_pending() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('packing_list');
        $this->db->where('status_scan_out', 'printed');
        $this->db->where('status_scan_in', 'pending');
        $query = $this->db->get();
        return $query->row()->total ?: 0;
    }

    public function get_stok_minimum_count() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('barang');
        $this->db->where('stok <= stok_minimum');
        $this->db->where('status', 'aktif');
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
        
        // Apply filter
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
        
        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('no_packing', $search);
            $this->db->or_like('customer', $search);
            $this->db->or_like('alamat', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_packing_list($filter = 'all', $search = '') {
        $this->db->from('packing_list');
        
        // Apply filter
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
        
        // Apply search
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
        // Get packing header
        $this->db->where('id', $id);
        $packing = $this->db->get('packing_list')->row_array();
        
        if (!$packing) {
            return false;
        }
        
        // Get packing items
        $this->db->select('pi.*, b.nama_barang, b.kategori, b.satuan');
        $this->db->from('packing_items pi');
        $this->db->join('barang b', 'pi.kode_barang = b.kode_barang', 'left');
        $this->db->where('pi.packing_id', $id);
        $items = $this->db->get()->result_array();
        
        $packing['items'] = $items;
        $packing['total_items'] = array_sum(array_column($items, 'qty'));
        
        return $packing;
    }

    public function save_packing_list($data, $items) {
        $this->db->trans_start();
        
        // Generate packing number if not provided
        if (empty($data['no_packing'])) {
            $data['no_packing'] = $this->generate_packing_number();
        }
        
        // Calculate total items
        $total_items = array_sum(array_column($items, 'qty'));
        $data['jumlah_item'] = $total_items;
        
        // Set default values
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Insert packing header
        $this->db->insert('packing_list', $data);
        $packing_id = $this->db->insert_id();
        
        // Insert packing items
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
        
        // Calculate total items
        $total_items = array_sum(array_column($items, 'qty'));
        $data['jumlah_item'] = $total_items;
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Update packing header
        $this->db->where('id', $packing_id);
        $this->db->update('packing_list', $data);
        
        // Delete existing items
        $this->db->where('packing_id', $packing_id);
        $this->db->delete('packing_items');
        
        // Insert new items
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
        
        // Delete items first
        $this->db->where('packing_id', $packing_id);
        $this->db->delete('packing_items');
        
        // Delete packing header
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

    // ==================== BARANG METHODS ====================

    public function get_all_barang() {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_barang_by_kategori($kategori) {
        $this->db->select('*');
        $this->db->from('barang');
        $this->db->where('kategori', $kategori);
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_barang_detail($kode_barang) {
        $this->db->where('kode_barang', $kode_barang);
        $this->db->where('status', 'aktif');
        $query = $this->db->get('barang');
        return $query->row_array();
    }

	// ==================== BARANG METHODS (Tambahan) ====================

	public function get_barang_by_id($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('barang');
		return $query->row_array();
	}

	// Check if kode barang exists
	public function is_kode_barang_exist($kode_barang) {
		$this->db->where('kode_barang', $kode_barang);
		$this->db->where('status', 'aktif');
		return $this->db->count_all_results('barang') > 0;
	}

	// Get barang for dropdown
	public function get_barang_for_dropdown() {
		$this->db->select('kode_barang, nama_barang, kategori, satuan, stok');
		$this->db->from('barang');
		$this->db->where('status', 'aktif');
		$this->db->order_by('nama_barang', 'ASC');
		$query = $this->db->get();
		
		$result = [];
		foreach ($query->result_array() as $row) {
			$result[] = $row;
		}
		return $result;
	}

    // ==================== KATEGORI METHODS ====================

    public function get_kategori_list() {
        $this->db->select('k.*, COUNT(b.id) as jumlah_barang');
        $this->db->from('kategori k');
        $this->db->join('barang b', 'k.kode_kategori = SUBSTRING(b.kode_barang, 1, 3)', 'left');
        $this->db->where('k.status', 'active');
        $this->db->group_by('k.id');
        $this->db->order_by('k.nama_kategori', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
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
            // Get barang count for this kategori
            $this->db->like('kode_barang', $kategori['kode_kategori'], 'after');
            $this->db->where('status', 'aktif');
            $kategori['jumlah_barang'] = $this->db->count_all_results('barang');
            
            // Get recent barang
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
        
        // Get last packing number
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
        $query = $this->db->get('packing_list');
        return $query->row_array();
    }

    public function get_today_scan_stats() {
        $today = date('Y-m-d');
        
        // Scan out today
        $this->db->where('DATE(scan_out_time)', $today);
        $this->db->where('status_scan_out', 'scanned_out');
        $scan_out_today = $this->db->count_all_results('packing_list');
        
        // Scan in today
        $this->db->where('DATE(scan_in_time)', $today);
        $this->db->where('status_scan_in', 'scanned_in');
        $scan_in_today = $this->db->count_all_results('packing_list');
        
        // Completed today
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
