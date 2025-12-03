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

    // public function get_packing_detail($id) {
    //     $this->db->where('id', $id);
    //     $packing = $this->db->get('packing_list')->row_array();
        
    //     if ($packing) {
    //         $this->db->select('pi.*,b.kode_barang, b.nama_barang, b.kategori, b.satuan');
    //         $this->db->from('packing_items pi');
    //         $this->db->join('barang b', 'pi.kode_barang = b.kode_barang', 'left');
    //         $this->db->where('pi.packing_id', $id);
    //         $packing['items'] = $this->db->get()->result_array();
    //         $packing['total_items'] = array_sum(array_column($packing['items'], 'qty'));
    //     }
        
    //     return $packing;
    // }

	public function get_packing_detail($id) {
		$this->db->where('id', $id);
		$packing = $this->db->get('packing_list')->row_array();
		
		if ($packing) {
			// Format tanggal
			$packing['tanggal_formatted'] = date('j F Y', strtotime($packing['tanggal']));
			$packing['created_at_formatted'] = date('j F Y', strtotime($packing['created_at']));
			
			// Ambil data customer dan alamat
			$packing['nama_customer'] = isset($packing['customer']) ? $packing['customer'] : 'Tidak diketahui';
			$packing['alamat'] = isset($packing['alamat_pengiriman']) ? $packing['alamat_pengiriman'] : '-';
			
			// Ambil item
			$this->db->select('pi.*, b.kode_barang as kode, b.nama_barang as nama, b.kategori, b.satuan');
			$this->db->from('packing_items pi');
			$this->db->join('barang b', 'pi.kode_barang = b.kode_barang', 'left');
			$this->db->where('pi.packing_id', $id);
			$items = $this->db->get()->result_array();
			
			$packing['items'] = $items;
			
			// Hitung total items dengan konversi ke integer
			$total_qty = 0;
			$category_summary = [];
			$unique_items = [];
			
			foreach ($items as $item) {
				// Konversi qty ke integer
				$qty = (int)$item['qty'];
				$total_qty += $qty;
				
				// Hitung summary per kategori
				$kategori = $item['kategori'] ?: 'Uncategorized';
				if (!isset($category_summary[$kategori])) {
					$category_summary[$kategori] = 0;
				}
				$category_summary[$kategori] += $qty;
				
				// Hitung item unik
				$key = $item['kode_barang'];
				if (!isset($unique_items[$key])) {
					$unique_items[$key] = $item;
				}
			}
			
			$packing['total_items'] = $total_qty;
			$packing['category_summary'] = $category_summary;
			$packing['total_unique_items'] = count($unique_items);
			
			// Status scan
			$packing['status_scan_out'] = isset($packing['status_scan_out']) ? $packing['status_scan_out'] : 'Label_foreach';
			$packing['status_scan_in'] = isset($packing['status_scan_in']) ? $packing['status_scan_in'] : 'Return Loading';
		}
		
		return $packing ?: [];
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

    // public function update_scan_status($packing_id, $status, $time_field = null, $clear_time = false) {
    //     $data = [];
        
    //     if (strpos($status, 'scanned_out') !== false || $status === 'printed') {
    //         $data['status_scan_out'] = $status;
    //     } elseif (strpos($status, 'scanned_in') !== false || $status === 'completed') {
    //         $data['status_scan_in'] = $status;
    //     }
        
    //     if ($time_field && !$clear_time) {
    //         $data[$time_field] = date('Y-m-d H:i:s');
    //     } elseif ($time_field && $clear_time) {
    //         $data[$time_field] = null;
    //     }
        
    //     $data['updated_at'] = date('Y-m-d H:i:s');
        
    //     $this->db->where('id', $packing_id);
    //     return $this->db->update('packing_list', $data);
    // }

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

    // public function get_today_scan_stats() {
    //     $today = date('Y-m-d');
        
    //     $this->db->where('DATE(scan_out_time)', $today);
    //     $this->db->where('status_scan_out', 'scanned_out');
    //     $scan_out_today = $this->db->count_all_results('packing_list');
        
    //     $this->db->where('DATE(scan_in_time)', $today);
    //     $this->db->where('status_scan_in', 'scanned_in');
    //     $scan_in_today = $this->db->count_all_results('packing_list');
        
    //     $this->db->where('DATE(scan_in_time)', $today);
    //     $this->db->where('status_scan_in', 'completed');
    //     $completed_today = $this->db->count_all_results('packing_list');
        
    //     return [
    //         'scan_out_today' => $scan_out_today,
    //         'scan_in_today' => $scan_in_today,
    //         'completed_today' => $completed_today
    //     ];
    // }

	// ==================== PACKING CETAK METHODS ====================

				/**
				 * Get packing by ID (for cetak)
				 */
				public function getPackingById($id) {
					$this->db->where('id', $id);
					$query = $this->db->get('packing_list');
					return $query->row();
				}

				/**
				* Get multiple packing by IDs
				*/
				public function getPackingByIds($ids) {
					if (empty($ids)) {
						return [];
					}
					
					$this->db->where_in('id', $ids);
					$query = $this->db->get('packing_list');
					return $query->result();
				}

				/**
				* Get packing items
				*/
				public function getPackingItems($packingId) {
					$this->db->where('packing_id', $packingId);
					return $this->db->get('packing_items')->result();
				}

				/**
				* Update packing status
				*/
				public function updatePackingStatus($packingId, $status) {
					$data = [
						'status_scan_out' => $status,
						'updated_at' => date('Y-m-d H:i:s')
					];
					
					$this->db->where('id', $packingId);
					return $this->db->update('packing_list', $data);
				}

				/**
				* Get packing status
				*/
				public function getPackingStatus($packingId) {
					$this->db->select('status_scan_out');
					$this->db->where('id', $packingId);
					$query = $this->db->get('packing_list');
					$result = $query->row();
					return $result ? $result->status_scan_out : null;
				}

				 // ==================== SCAN-RELATED METHODS ====================
    
			/**
			 * Get today's scan statistics
			 */
			public function get_today_scan_stats() {
				$today = date('Y-m-d');
				
				$stats = [
					'scanned_today' => $this->db
						->where('DATE(scan_out_time)', $today)
						->where('status_scan_out', 'scanned_out')
						->count_all_results('packing_list'),
					
					'loaded_today' => $this->db
						->where('DATE(scan_in_time)', $today)
						->where('status_scan_in', 'scanned_in')
						->count_all_results('packing_list'),
					
					'completed_today' => $this->db
						->where('DATE(scan_in_time)', $today)
						->where('status_scan_in', 'completed')
						->count_all_results('packing_list')
				];
				
				return $stats;
			}
			
			/**
			 * Get recent scan activities
			 */
			public function get_recent_scans($limit = 5) {
				$this->db->select('sl.*, p.no_packing, p.customer');
				$this->db->from('scan_logs sl');
				$this->db->join('packing_list p', 'sl.packing_id = p.id', 'left');
				$this->db->order_by('sl.timestamp', 'DESC');
				$this->db->limit($limit);
				
				return $this->db->get()->result_array();
			}
			
			/**
			 * Get packing by ID untuk scan
			 */
			public function get_packing_for_scan($id) {
				$this->db->select('p.*, COUNT(pi.id) as jumlah_item');
				$this->db->from('packing_list p');
				$this->db->join('packing_items pi', 'p.id = pi.packing_id', 'left');
				$this->db->where('p.id', $id);
				$this->db->group_by('p.id');
				
				$query = $this->db->get();
				return $query->row_array();
			}
			
			/**
			 * Update scan status
			 */
			public function update_scan_status($packing_id, $data) {
				$this->db->where('id', $packing_id);
				return $this->db->update('packing_list', $data);
			}
			
			/**
			 * Log scan activity
			 */
			public function log_scan_activity($log_data) {
				return $this->db->insert('scan_logs', $log_data);
			}

			public function get_packing_by_ids($ids)
			{
				if (empty($ids) || !is_array($ids)) {
					return [];
				}
				
				$this->db->select('p.*, 
					(SELECT COUNT(*) FROM packing_items WHERE packing_id = p.id) as jumlah_item');
				$this->db->from('packing_list p');
				$this->db->where_in('p.id', $ids);
				$this->db->order_by('FIELD(p.id, ' . implode(',', $ids) . ')');
				
				return $this->db->get()->result();
			}

			// Alternative jika FIELD() tidak support (MySQL)
			public function get_packing_by_ids_simple($ids)
			{
				if (empty($ids) || !is_array($ids)) {
					return [];
				}
				
				$this->db->select('p.*, 
					(SELECT COUNT(*) FROM packing_items WHERE packing_id = p.id) as jumlah_item');
				$this->db->from('packing_list p');
				$this->db->where_in('p.id', $ids);
				$this->db->order_by('p.created_at', 'DESC');
				
				return $this->db->get()->result();
			}


			// ==================== LABEL METHODS ====================

			/**
			 * Generate label code
			 */
			public function generate_label_code($prefix = 'LBL', $length = 6) {
				do {
					$random = strtoupper(bin2hex(random_bytes(ceil($length/2))));
					$label_code = $prefix . substr($random, 0, $length);
					
					// Check if exists
					$this->db->where('label_code', $label_code);
					$exists = $this->db->count_all_results('labels') > 0;
				} while ($exists);
				
				return $label_code;
			}

			/**
			 * Create labels for packing list
			 */
			public function create_labels_for_packing($packing_id, $label_count = 1, $label_type = 'single') {
				$this->db->trans_start();
				
				// Check if packing exists
				$this->db->where('id', $packing_id);
				$packing = $this->db->get('packing_list')->row_array();
				
				if (!$packing) {
					return false;
				}
				
				$labels = [];
				
				// Create master label for multi-label system
				if ($label_count > 1) {
					$master_label = [
						'label_code' => $this->generate_label_code('MLBL'),
						'packing_id' => $packing_id,
						'label_type' => 'master',
						'status' => 'active',
						'created_at' => date('Y-m-d H:i:s')
					];
					
					$this->db->insert('labels', $master_label);
					$master_label_id = $this->db->insert_id();
					
					// Create child labels
					for ($i = 1; $i <= $label_count; $i++) {
						$child_label = [
							'label_code' => $this->generate_label_code('CLBL'),
							'packing_id' => $packing_id,
							'label_type' => 'child',
							'parent_label_id' => $master_label_id,
							'status' => 'active',
							'created_at' => date('Y-m-d H:i:s')
						];
						
						$this->db->insert('labels', $child_label);
						$labels[] = array_merge($child_label, ['id' => $this->db->insert_id()]);
					}
					
					// Update master label status
					$this->db->where('id', $master_label_id);
					$this->db->update('labels', ['total_children' => $label_count]);
					
				} else {
					// Single label system
					$label_data = [
						'label_code' => $this->generate_label_code('LBL'),
						'packing_id' => $packing_id,
						'label_type' => 'single',
						'status' => 'active',
						'created_at' => date('Y-m-d H:i:s')
					];
					
					$this->db->insert('labels', $label_data);
					$label_id = $this->db->insert_id();
					$labels[] = array_merge($label_data, ['id' => $label_id]);
				}
				
				$this->db->trans_complete();
				
				return $this->db->trans_status() ? $labels : false;
			}

			/**
			 * Get label by code
			 */
			public function get_label_by_code($label_code) {
				$this->db->select('l.*, p.*, 
					COUNT(DISTINCT lc.id) as total_children,
					SUM(CASE WHEN lc.status = "scanned_out" THEN 1 ELSE 0 END) as scanned_out_children,
					SUM(CASE WHEN lc.status = "scanned_in" THEN 1 ELSE 0 END) as scanned_in_children,
					SUM(CASE WHEN lc.status = "completed" THEN 1 ELSE 0 END) as completed_children');
				$this->db->from('labels l');
				$this->db->join('packing_list p', 'l.packing_id = p.id', 'left');
				$this->db->join('labels lc', 'lc.parent_label_id = l.id AND l.label_type = "master"', 'left');
				$this->db->where('l.label_code', $label_code);
				$this->db->group_by('l.id');
				
				return $this->db->get()->row_array();
			}

			/**
			 * Get all labels for packing list
			 */
			public function get_labels_by_packing($packing_id) {
				$this->db->select('l.*');
				$this->db->from('labels l');
				$this->db->where('l.packing_id', $packing_id);
				$this->db->order_by('l.label_type', 'DESC');
				$this->db->order_by('l.id', 'ASC');
				
				return $this->db->get()->result_array();
			}

			/**
			 * Process label scan
			 */
			public function process_label_scan($label_id, $action, $user_id = null) {
				$valid_actions = ['printed', 'scanned_out', 'scanned_in', 'completed', 'void'];
				
				if (!in_array($action, $valid_actions)) {
					return false;
				}
				
				$time_field_map = [
					'printed' => 'printed_at',
					'scanned_out' => 'scanned_out_at',
					'scanned_in' => 'scanned_in_at',
					'completed' => 'completed_at'
				];
				
				$this->db->trans_start();
				
				// Update label status
				$update_data = [
					'status' => $action,
					'updated_at' => date('Y-m-d H:i:s')
				];
				
				if (isset($time_field_map[$action])) {
					$update_data[$time_field_map[$action]] = date('Y-m-d H:i:s');
				}
				
				$this->db->where('id', $label_id);
				$this->db->update('labels', $update_data);
				
				// Log the scan
				$log_data = [
					'label_id' => $label_id,
					'action' => $action,
					'user_id' => $user_id,
					'scan_time' => date('Y-m-d H:i:s')
				];
				$this->db->insert('label_scan_logs', $log_data);
				
				// If this is a child label, check parent status
				$this->db->select('parent_label_id, status');
				$this->db->where('id', $label_id);
				$label = $this->db->get('labels')->row_array();
				
				if ($label && $label['parent_label_id']) {
					$this->_update_parent_label_status($label['parent_label_id']);
				}
				
				// Update packing list status if all labels are scanned
				$this->db->select('packing_id');
				$this->db->where('id', $label_id);
				$packing_id_result = $this->db->get('labels')->row();
				
				if ($packing_id_result) {
					$this->_update_packing_status($packing_id_result->packing_id);
				}
				
				$this->db->trans_complete();
				
				return $this->db->trans_status();
			}

			/**
			 * Update parent label status based on children
			 */
			private function _update_parent_label_status($parent_label_id) {
				$this->db->select('
					COUNT(*) as total_children,
					SUM(CASE WHEN status = "scanned_out" THEN 1 ELSE 0 END) as scanned_out,
					SUM(CASE WHEN status = "scanned_in" THEN 1 ELSE 0 END) as scanned_in,
					SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'
				);
				$this->db->where('parent_label_id', $parent_label_id);
				$stats = $this->db->get('labels')->row_array();
				
				$new_status = 'active';
				
				if ($stats['completed'] == $stats['total_children']) {
					$new_status = 'completed';
				} elseif ($stats['scanned_in'] == $stats['total_children']) {
					$new_status = 'scanned_in';
				} elseif ($stats['scanned_out'] == $stats['total_children']) {
					$new_status = 'scanned_out';
				}
				
				$this->db->where('id', $parent_label_id);
				$this->db->update('labels', ['status' => $new_status]);
			}

			/**
			 * Update packing list status based on labels
			 */
			private function _update_packing_status($packing_id) {
				$this->db->select('
					COUNT(*) as total_labels,
					SUM(CASE WHEN status = "scanned_out" THEN 1 ELSE 0 END) as scanned_out,
					SUM(CASE WHEN status = "scanned_in" THEN 1 ELSE 0 END) as scanned_in,
					SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'
				);
				$this->db->where('packing_id', $packing_id);
				$stats = $this->db->get('labels')->row_array();
				
				$update_data = [];
				
				if ($stats['completed'] > 0) {
					$update_data['status_scan_in'] = 'completed';
				} elseif ($stats['scanned_in'] > 0) {
					$update_data['status_scan_in'] = 'scanned_in';
				} elseif ($stats['scanned_out'] > 0) {
					$update_data['status_scan_out'] = 'scanned_out';
				}
				
				if (!empty($update_data)) {
					$this->db->where('id', $packing_id);
					$this->db->update('packing_list', $update_data);
				}
			}

			/**
			 * Get label statistics
			 */
			public function get_label_statistics($date = null) {
				if (!$date) {
					$date = date('Y-m-d');
				}
				
				$stats = [
					'total_labels' => $this->db->where('DATE(created_at)', $date)->count_all_results('labels'),
					'printed_today' => $this->db->where('DATE(printed_at)', $date)->count_all_results('labels'),
					'scanned_out_today' => $this->db->where('DATE(scanned_out_at)', $date)->count_all_results('labels'),
					'scanned_in_today' => $this->db->where('DATE(scanned_in_at)', $date)->count_all_results('labels'),
					'completed_today' => $this->db->where('DATE(completed_at)', $date)->count_all_results('labels'),
				];
				
				return $stats;
			}


}
