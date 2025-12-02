<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Gudang_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Dashboard
    public function index() {
		$this->load->model('Dashboard_model'); // Tambahkan ini jika belum ada
		
		$data = array(
			'title' => 'Dashboard - KENDA',
			'username' => $this->session->userdata('username'),
			'active_menu' => 'dashboard',
			'content' => 'dashboard/index',
			'total_barang' => $this->Gudang_model->get_total_barang(),
			'total_tube' => $this->Gudang_model->get_total_by_kategori('Tube'),
			'total_tire' => $this->Gudang_model->get_total_by_kategori('Tire'),
			'packing_pending' => $this->Gudang_model->get_packing_pending(),
			'stok_minimum' => $this->Gudang_model->get_stok_minimum_count()
		);
		
		// Ambil data untuk dashboard
		$data['dashboard_stats'] = $this->Dashboard_model->get_dashboard_stats();
		$data['recent_activities'] = $this->Dashboard_model->get_recent_activities(5);
		$data['recent_packing'] = $this->Dashboard_model->get_recent_packing(5);
		$data['monthly_stats'] = $this->Dashboard_model->get_monthly_stats(date('m'), date('Y'));
		$data['tube_stats'] = $this->Dashboard_model->get_category_stats('Tube');
		$data['tire_stats'] = $this->Dashboard_model->get_category_stats('Tire');
		$data['stok_comparison'] = $this->Dashboard_model->get_stok_comparison_data();
		
		$this->load->view('template', $data);
	}

		public function cetak_packing($id) {
		$packing = $this->Gudang_model->get_packing_detail($id);
		
		if (!$packing) {
			show_404();
		}
		
		$data['packing'] = $packing;
		$this->load->view('packing list/cetak', $data);
	}

    // Laporan Stok
    public function stok() {
        $data = array(
            'title' => 'Laporan Stok - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'stok',
            'content' => 'laporan stok/index',
            'total_barang' => $this->Gudang_model->get_total_barang(),
            'total_tube' => $this->Gudang_model->get_total_by_kategori('Tube'),
            'total_tire' => $this->Gudang_model->get_total_by_kategori('Tire'),
            'stok_minimum' => $this->Gudang_model->get_stok_minimum_count()
        );
        
        $this->load->view('template', $data);
    }

    // Data Barang
   // Data Barang
public function barang() {
    $page = $this->input->get('page') ?: 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $search = $this->input->get('search') ?: '';
    $filter = $this->input->get('filter') ?: 'all';
    
    $total_barang = $this->Gudang_model->get_total_barang();
    $total_tube = $this->Gudang_model->get_total_by_kategori('Tube');
    $total_tire = $this->Gudang_model->get_total_by_kategori('Tire');
    $stok_minimum = $this->Gudang_model->get_stok_minimum_count();
    
    // Get barang list with pagination
    $barang_list = $this->Gudang_model->get_barang_paginated($limit, $offset, $search, $filter);
    $total_filtered = $this->Gudang_model->count_barang($search, $filter);
    $total_pages = ceil($total_filtered / $limit);
    
    $data = array(
        'title' => 'Data Barang - KENDA',
        'username' => $this->session->userdata('username'),
        'active_menu' => 'barang',
        'content' => 'barang/index',
        'total_barang' => $total_barang,
        'total_tube' => $total_tube,
        'total_tire' => $total_tire,
        'stok_minimum' => $stok_minimum,
        'barang_list' => $barang_list,
        'current_page' => (int)$page,
        'total_pages' => $total_pages,
        'total_filtered' => $total_filtered,
        'search' => $search,
        'filter' => $filter,
        'limit' => $limit
    );
    
    $this->load->view('template', $data);
}
    // Scan Label
    public function scan() {
        $data = array(
            'title' => 'Scan Label - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'scan',
            'content' => 'scan label/index',
            'today_stats' => $this->Gudang_model->get_today_scan_stats()
        );
        
        $this->load->view('template', $data);
    }

    // Packing List
    public function packing_list() {
        $data = array(
            'title' => 'Packing List - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'packing',
            'content' => 'packing list/index',
            'total_packing' => $this->Gudang_model->get_total_packing(),
            'pending_packing' => $this->Gudang_model->get_pending_packing(),
            'completed_packing' => $this->Gudang_model->get_completed_packing(),
            'total_items' => $this->Gudang_model->get_total_items_packed()
        );
        
        $this->load->view('template', $data);
    }

    // Kategori Barang
    public function kategori() {
        $data = array(
            'title' => 'Kategori Barang - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'kategori',
            'content' => 'kategori/index',
            'statistics' => $this->Gudang_model->get_kategori_statistics()
        );
        
        $this->load->view('template', $data);
    }

    // ==================== BARANG API METHODS ====================

    /**
     * API untuk Detail Barang
     */
    public function api_detail_barang($kode_barang) {
        $barang = $this->Gudang_model->get_barang_detail($kode_barang);
        
        if ($barang) {
            $response = [
                'success' => true,
                'data' => $barang
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk List Barang (AJAX)
     */
    public function api_list_barang() {
        $barang_list = $this->Gudang_model->get_all_barang();
        
        $response = [
            'success' => true,
            'data' => $barang_list
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Tambah Barang
     */
    public function tambah_barang() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|max_length[20]');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|max_length[100]');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];
        } else {
            $kode_barang = $this->input->post('kode_barang');
            
            // Check if kode barang already exists
            if ($this->Gudang_model->is_kode_barang_exist($kode_barang)) {
                $response = [
                    'success' => false,
                    'message' => 'Kode barang sudah digunakan'
                ];
            } else {
                $data = [
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $this->input->post('nama_barang'),
                    'kategori' => $this->input->post('kategori'),
                    'satuan' => $this->input->post('satuan'),
                    'stok_minimum' => $this->input->post('stok_minimum') ?: 0,
                    'status' => $this->input->post('status') ?: 'aktif',
                    'deskripsi' => $this->input->post('deskripsi'),
                    'stok' => 0, // Default stok 0, akan diisi via stok awal
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $result = $this->Gudang_model->tambah_barang($data);
                
                if ($result) {
                    $response = [
                        'success' => true,
                        'message' => 'Barang berhasil ditambahkan'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Gagal menambahkan barang'
                    ];
                }
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Update Barang
     */
    public function update_barang() {
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|max_length[100]');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];
        } else {
            $kode_barang = $this->input->post('kode_barang');
            
            $data = [
                'nama_barang' => $this->input->post('nama_barang'),
                'kategori' => $this->input->post('kategori'),
                'satuan' => $this->input->post('satuan'),
                'stok_minimum' => $this->input->post('stok_minimum') ?: 0,
                'status' => $this->input->post('status'),
                'deskripsi' => $this->input->post('deskripsi'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->Gudang_model->update_barang($kode_barang, $data);
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Barang berhasil diupdate'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Gagal mengupdate barang'
                ];
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Hapus Barang
     */
    public function hapus_barang($kode_barang) {
        if (empty($kode_barang)) {
            $response = [
                'success' => false,
                'message' => 'Kode barang tidak valid'
            ];
        } else {
            $result = $this->Gudang_model->hapus_barang($kode_barang);
            
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Barang berhasil dihapus'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Gagal menghapus barang'
                ];
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Input Stok Awal
     */
    public function stok_awal() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required');
        $this->form_validation->set_rules('stok_awal', 'Stok Awal', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];
        } else {
            $kode_barang = $this->input->post('kode_barang');
            $stok_awal = $this->input->post('stok_awal');
            
            // Update stok barang
            $result = $this->Gudang_model->update_stok_barang($kode_barang, $stok_awal);
            
            if ($result) {
                // Log stok awal
                $log_data = [
                    'kode_barang' => $kode_barang,
                    'jenis' => 'stok_awal',
                    'qty' => $stok_awal,
                    'keterangan' => $this->input->post('keterangan') ?: 'Stok awal barang',
                    'tanggal' => $this->input->post('tanggal'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->Gudang_model->add_stok_log($log_data);
                
                $response = [
                    'success' => true,
                    'message' => 'Stok awal berhasil disimpan'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Gagal menyimpan stok awal'
                ];
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Barang Masuk
     */
    public function barang_masuk() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];
        } else {
            $kode_barang = $this->input->post('kode_barang');
            $jumlah = $this->input->post('jumlah');
            
            // Get current stok
            $barang = $this->Gudang_model->get_barang_detail($kode_barang);
            
            if (!$barang) {
                $response = [
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ];
            } else {
                $stok_baru = $barang['stok'] + $jumlah;
                
                // Update stok barang
                $result = $this->Gudang_model->update_stok_barang($kode_barang, $stok_baru);
                
                if ($result) {
                    // Log barang masuk
                    $log_data = [
                        'kode_barang' => $kode_barang,
                        'jenis' => 'masuk',
                        'qty' => $jumlah,
                        'supplier' => $this->input->post('supplier') ?: '',
                        'no_po' => $this->input->post('no_po') ?: '',
                        'keterangan' => $this->input->post('keterangan') ?: 'Barang masuk',
                        'tanggal' => $this->input->post('tanggal'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->Gudang_model->add_stok_log($log_data);
                    
                    $response = [
                        'success' => true,
                        'message' => 'Barang masuk berhasil disimpan'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Gagal menyimpan barang masuk'
                    ];
                }
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Barang Keluar
     */
    public function barang_keluar() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];
        } else {
            $kode_barang = $this->input->post('kode_barang');
            $jumlah = $this->input->post('jumlah');
            
            // Get current stok
            $barang = $this->Gudang_model->get_barang_detail($kode_barang);
            
            if (!$barang) {
                $response = [
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ];
            } elseif ($barang['stok'] < $jumlah) {
                $response = [
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang['stok']
                ];
            } else {
                $stok_baru = $barang['stok'] - $jumlah;
                
                // Update stok barang
                $result = $this->Gudang_model->update_stok_barang($kode_barang, $stok_baru);
                
                if ($result) {
                    // Log barang keluar
                    $log_data = [
                        'kode_barang' => $kode_barang,
                        'jenis' => 'keluar',
                        'qty' => $jumlah,
                        'customer' => $this->input->post('customer') ?: '',
                        'no_sj' => $this->input->post('no_sj') ?: '',
                        'keterangan' => $this->input->post('keterangan') ?: 'Barang keluar',
                        'tanggal' => $this->input->post('tanggal'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->Gudang_model->add_stok_log($log_data);
                    
                    $response = [
                        'success' => true,
                        'message' => 'Barang keluar berhasil disimpan'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Gagal menyimpan barang keluar'
                    ];
                }
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Adjustment Stok
     */
    public function adjustment_stok() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required');
        $this->form_validation->set_rules('stok_baru', 'Stok Baru', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('alasan', 'Alasan Adjustment', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];
        } else {
            $kode_barang = $this->input->post('kode_barang');
            $stok_baru = $this->input->post('stok_baru');
            
            // Get current stok
            $barang = $this->Gudang_model->get_barang_detail($kode_barang);
            
            if (!$barang) {
                $response = [
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ];
            } else {
                $selisih = $stok_baru - $barang['stok'];
                
                // Update stok barang
                $result = $this->Gudang_model->update_stok_barang($kode_barang, $stok_baru);
                
                if ($result) {
                    // Log adjustment
                    $log_data = [
                        'kode_barang' => $kode_barang,
                        'jenis' => 'adjustment',
                        'qty' => $selisih,
                        'stok_sebelum' => $barang['stok'],
                        'stok_sesudah' => $stok_baru,
                        'keterangan' => $this->input->post('alasan') ?: 'Adjustment stok',
                        'tanggal' => date('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->Gudang_model->add_stok_log($log_data);
                    
                    $response = [
                        'success' => true,
                        'message' => 'Adjustment stok berhasil disimpan'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Gagal menyimpan adjustment stok'
                    ];
                }
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Export Data Barang
     */
    public function export_barang() {
        $this->load->helper('download');
        
        $data = $this->Gudang_model->get_barang_for_export();
        
        $csv = "Kode Barang,Nama Barang,Kategori,Stok,Satuan,Stok Minimum,Status,Deskripsi\n";
        
        foreach ($data as $barang) {
            $csv .= '"' . $barang['kode_barang'] . '","' 
                    . $barang['nama_barang'] . '","' 
                    . $barang['kategori'] . '","' 
                    . $barang['stok'] . '","' 
                    . $barang['satuan'] . '","' 
                    . $barang['stok_minimum'] . '","' 
                    . $barang['status'] . '","' 
                    . str_replace('"', '""', $barang['deskripsi'] ?? '') . '"' . "\n";
        }
        
        $filename = 'data_barang_' . date('Y-m-d') . '.csv';
        force_download($filename, $csv);
    }

    /**
     * API untuk Get Barang Statistics
     */
    public function api_barang_statistics() {
        $statistics = $this->Gudang_model->get_barang_statistics();
        
        $response = [
            'success' => true,
            'data' => $statistics
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Get Barang Paginated
     */
    public function api_list_barang_paginated() {
        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        $search = $this->input->get('search') ?: '';
        $filter = $this->input->get('filter') ?: 'all';
        
        $offset = ($page - 1) * $limit;
        
        $result = $this->Gudang_model->get_barang_paginated($limit, $offset, $search, $filter);
        $total = $this->Gudang_model->count_barang($search, $filter);
        
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

    // ==================== KATEGORI API METHODS ====================

		public function api_list_kategori() {
		try {
			// Get parameters from request
			$page = $this->input->get('page') ? intval($this->input->get('page')) : 1;
			$limit = $this->input->get('limit') ? intval($this->input->get('limit')) : 10;
			$status = $this->input->get('status') ? $this->input->get('status') : 'all';
			
			// Validate parameters
			$page = max(1, $page);
			$limit = max(1, min(100, $limit)); // Limit max 100 items per page
			$offset = ($page - 1) * $limit;
			
			// Get total count with filter
			$this->db->select('COUNT(*) as total');
			$this->db->from('kategori');
			
			if ($status !== 'all') {
				$this->db->where('status', $status);
			}
			
			$total_result = $this->db->get()->row_array();
			$total_items = $total_result['total'] ?? 0;
			
			// Get paginated data with filter
			$this->db->select('k.*, COUNT(b.id) as jumlah_barang');
			$this->db->from('kategori k');
			$this->db->join('barang b', 'k.kode_kategori = SUBSTRING(b.kode_barang, 1, 3)', 'left');
			
			if ($status !== 'all') {
				$this->db->where('k.status', $status);
			}
			
			$this->db->group_by('k.id');
			$this->db->order_by('k.nama_kategori', 'ASC');
			$this->db->limit($limit, $offset);
			
			$data = $this->db->get()->result_array();
			
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
				'status_filter' => $status
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
        $statistics = $this->Gudang_model->get_kategori_statistics();
        
        $response = [
            'success' => true,
            'data' => $statistics
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_detail_kategori($id) {
        $kategori = $this->Gudang_model->get_kategori_detail($id);
        
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

        $result = $this->Gudang_model->save_kategori($data);

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

        $result = $this->Gudang_model->update_kategori($id, $data);

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
        $result = $this->Gudang_model->delete_kategori($id);

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

    // ==================== PACKING LIST API METHODS ====================

    public function api_list_packing() {
        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        $filter = $this->input->get('filter') ?: 'all';
        $search = $this->input->get('search') ?: '';

        $offset = ($page - 1) * $limit;

        $result = $this->Gudang_model->get_packing_list($limit, $offset, $filter, $search);
        $total = $this->Gudang_model->count_packing_list($filter, $search);

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
        $packing = $this->Gudang_model->get_packing_detail($id);
        
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

        $result = $this->Gudang_model->save_packing_list($data, $items);

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

        $result = $this->Gudang_model->update_packing_list($packing_id, $data, $items);

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
        $result = $this->Gudang_model->delete_packing_list($id);

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

    // ==================== SCAN API METHODS ====================

    public function api_scan_out() {
        $packing_id = $this->input->post('packing_id');
        
        $result = $this->Gudang_model->update_scan_status($packing_id, 'scanned_out', 'scan_out_time');

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
        
        $result = $this->Gudang_model->update_scan_status($packing_id, 'printed', 'scan_out_time', true);

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
        
        $result = $this->Gudang_model->update_scan_status($packing_id, 'scanned_in', 'scan_in_time');

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
        
        $result = $this->Gudang_model->update_scan_status($packing_id, 'completed', 'scan_in_time');

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

    public function api_cetak_label() {
        $packing_ids = $this->input->post('packing_ids');
        $type = $this->input->post('type');

        $labels = [];
        foreach ($packing_ids as $id) {
            $packing = $this->Gudang_model->get_packing_detail($id);
            if ($packing) {
                $labels[] = [
                    'no_packing' => $packing['no_packing'],
                    'customer' => $packing['customer'],
                    'tanggal' => $packing['tanggal'],
                    'items' => $packing['items']
                ];
            }
        }

        $response = [
            'success' => true,
            'data' => [
                'labels' => $labels,
                'total' => count($labels),
                'type' => $type
            ]
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_check_label($label_code) {
        $packing = $this->Gudang_model->get_packing_by_label($label_code);
        
        if ($packing) {
            $response = [
                'success' => true,
                'data' => $packing
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

    public function api_process_scan() {
        $packing_id = $this->input->post('packing_id');
        $action = $this->input->post('action');
        $label_code = $this->input->post('label_code');

        switch ($action) {
            case 'scanned_out':
                $result = $this->Gudang_model->update_scan_status($packing_id, 'scanned_out', 'scan_out_time');
                break;
            case 'scanned_in':
                $result = $this->Gudang_model->update_scan_status($packing_id, 'scanned_in', 'scan_in_time');
                break;
            case 'completed':
                $result = $this->Gudang_model->update_scan_status($packing_id, 'completed', 'scan_in_time');
                break;
            default:
                $result = false;
        }

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Scan berhasil diproses',
                'data' => [
                    'packing_id' => $packing_id,
                    'action' => $action,
                    'label_code' => $label_code,
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

    public function api_today_scan_stats() {
        $stats = $this->Gudang_model->get_today_scan_stats();
        
        $response = [
            'success' => true,
            'data' => $stats
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
