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

	// 	public function cetak_packing($id) {
	// 	$packing = $this->Gudang_model->get_packing_detail($id);
		
	// 	if (!$packing) {
	// 		show_404();
	// 	}
		
	// 	$data['packing'] = $packing;
	// 	$this->load->view('packing list/cetak', $data);
	// }

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

    // public function api_check_label($label_code) {
    //     $packing = $this->Gudang_model->get_packing_by_label($label_code);
        
    //     if ($packing) {
    //         $response = [
    //             'success' => true,
    //             'data' => $packing
    //         ];
    //     } else {
    //         $response = [
    //             'success' => false,
    //             'message' => 'Label tidak ditemukan'
    //         ];
    //     }

    //     $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode($response));
    // }

    // public function api_process_scan() {
    //     $packing_id = $this->input->post('packing_id');
    //     $action = $this->input->post('action');
    //     $label_code = $this->input->post('label_code');

    //     switch ($action) {
    //         case 'scanned_out':
    //             $result = $this->Gudang_model->update_scan_status($packing_id, 'scanned_out', 'scan_out_time');
    //             break;
    //         case 'scanned_in':
    //             $result = $this->Gudang_model->update_scan_status($packing_id, 'scanned_in', 'scan_in_time');
    //             break;
    //         case 'completed':
    //             $result = $this->Gudang_model->update_scan_status($packing_id, 'completed', 'scan_in_time');
    //             break;
    //         default:
    //             $result = false;
    //     }

    //     if ($result) {
    //         $response = [
    //             'success' => true,
    //             'message' => 'Scan berhasil diproses',
    //             'data' => [
    //                 'packing_id' => $packing_id,
    //                 'action' => $action,
    //                 'label_code' => $label_code,
    //                 'timestamp' => date('Y-m-d H:i:s')
    //             ]
    //         ];
    //     } else {
    //         $response = [
    //             'success' => false,
    //             'message' => 'Gagal memproses scan'
    //         ];
    //     }

    //     $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode($response));
    // }

    // public function api_today_scan_stats() {
    //     $stats = $this->Gudang_model->get_today_scan_stats();
        
    //     $response = [
    //         'success' => true,
    //         'data' => $stats
    //     ];

    //     $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode($response));
    // }

	// ==================== PACKING LIST API METHODS ====================

/**
 * API untuk Get Barang untuk Packing List
 */
public function api_barang_for_packing() {
    $barang_list = $this->Gudang_model->get_barang_for_dropdown();
    
    $formatted_data = [];
    foreach ($barang_list as $barang) {
        $formatted_data[] = [
            'id' => $barang['kode_barang'],
            'kode' => $barang['kode_barang'],
            'nama' => $barang['nama_barang'],
            'kategori' => $barang['kategori'],
            'stok' => $barang['stok'],
            'satuan' => $barang['satuan']
        ];
    }
    
    $response = [
        'success' => true,
        'data' => $formatted_data
    ];

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

	public function cetak_packing($id) {
		// Ambil data dari model
		$packing = $this->Gudang_model->get_packing_detail($id);
		
		if (!$packing) {
			show_404();
		}
		
		// Pastikan data items ada
		if (!isset($packing['items']) || empty($packing['items'])) {
			$packing['items'] = [];
		}
		
		// Hitung total items
		$packing['total_items'] = isset($packing['jumlah_item']) ? $packing['jumlah_item'] : 0;
		
		$data['packing'] = $packing;
		
		// Load view cetak
		$this->load->view('cetak', $data);
	}

		public function cetak_label($id = null)
	{
		// Ambil data packing list
		$data['packing'] = $this->Gudang_model->getPackingById($id);
		
		// Load view cetak label
		$this->load->view('packing list/cetak_label', $data);
	}

	// public function cetak_label_multiple()
	// {
	// 	$ids = $this->input->get('ids');
	// 	$id_array = explode(',', $ids);
		
	// 	// Ambil data multiple packing
	// 	$data['packings'] = $this->Gudang_model->getPackingByIds($id_array);
		
	// 	// Load view cetak multiple
	// 	$this->load->view('packing list/cetak_label_multiple', $data);
	// }

	// Di application/controllers/Gudang.php
public function cetak_label_multiple()
{
    try {
        // Get IDs from query string
        $ids = $this->input->get('ids');
        
        if (empty($ids)) {
            // Default untuk testing
            $ids = '1,2,3,4,5';
        }
        
        // Convert string to array
        $ids_array = explode(',', $ids);
        $ids_array = array_map('intval', $ids_array);
        $ids_array = array_filter($ids_array);
        
        if (empty($ids_array)) {
            // Show empty state
            $data['packing_lists'] = [];
            $data['total_labels'] = 0;
            $data['error_message'] = 'Tidak ada ID packing list yang valid';
        } else {
            // Load model jika belum
            if (!class_exists('Packing_list_model')) {
                $this->load->model('Gudang_model', 'packing');
            }
            
            // Get packing lists data
            $packing_lists = $this->db
                ->select('p.*, 
                    (SELECT COUNT(*) FROM packing_items WHERE packing_id = p.id) as jumlah_item')
                ->from('packing_list p')
                ->where_in('p.id', $ids_array)
                ->get()
                ->result();
            
            if (empty($packing_lists)) {
                // Jika tidak ada data, coba query sederhana
                $packing_lists = $this->db
                    ->where_in('id', $ids_array)
                    ->get('packing_list')
                    ->result();
                
                // Tambahkan jumlah_item default
                foreach ($packing_lists as &$list) {
                    $list->jumlah_item = 0;
                }
            }
            
            $data['packing_lists'] = $packing_lists;
            $data['total_labels'] = count($packing_lists);
            $data['error_message'] = null;
        }
        
        // Tambahkan data tambahan
        $data['print_date'] = date('d/m/Y H:i:s');
        $data['page_title'] = 'Cetak Label Packing List';
        
        // Debug info (hapus di production)
        $data['debug_info'] = [
            'ids_input' => $ids,
            'ids_array' => $ids_array,
            'count' => isset($packing_lists) ? count($packing_lists) : 0
        ];
        
        // Load view
        $this->load->view('packing list/cetak_label_multiple', $data);
        
    } catch (Exception $e) {
        // Error handling
        $data['packing_lists'] = [];
        $data['total_labels'] = 0;
        $data['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $data['print_date'] = date('d/m/Y H:i:s');
        
        $this->load->view('packing list/cetak_label_multiple', $data);
    }
}

 // ==================== SCAN API METHODS YANG DISINKRONKAN ====================

    /**
     * API untuk Check Label - DISINKRONKAN
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
     * API untuk Process Scan - DISINKRONKAN dengan Packing List
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
     * API untuk Today Scan Statistics - DISINKRONKAN
     */
    public function api_today_scan_stats() {
        $today = date('Y-m-d');
        
        // Hitung statistik berdasarkan packing list
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
        
        $response = [
            'success' => true,
            'data' => $stats
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API untuk Recent Scans - DISINKRONKAN
     */
    public function api_recent_scans($limit = 5) {
        $this->db->select('sl.*, p.no_packing, p.customer');
        $this->db->from('scan_logs sl');
        $this->db->join('packing_list p', 'sl.packing_id = p.id', 'left');
        $this->db->order_by('sl.timestamp', 'DESC');
        $this->db->limit($limit);
        
        $scans = $this->db->get()->result_array();
        
        $response = [
            'success' => true,
            'data' => $scans
        ];

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

	// di Controller Packing_list.php

public function api_update_status()
{
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
    
    // Update status di database
    $data = [
        'status_scan_out' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $this->db->where('id', $packing_id);
    $result = $this->db->update('packing_list', $data);
    
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



			// ==================== LABEL API METHODS ====================

			/**
			 * API untuk generate labels untuk packing list
			 */

			// Di Gudang.php - perbaiki method api_generate_label_format()
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
        $labels = $this->Gudang_model->generate_labels_with_format(
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
 * API untuk check label (single dan multi)
 */
// public function api_check_label($label_code) {
//     $label = $this->Gudang_model->get_label_by_code($label_code);
    
//     if ($label) {
//         // Get packing details
//         $packing = $this->Gudang_model->get_packing_detail($label['packing_id']);
        
//         // Get all labels for this packing
//         $all_labels = $this->Gudang_model->get_labels_by_packing($label['packing_id']);
        
//         // Determine next valid actions
//         $next_actions = $this->_get_next_label_actions($label['status'], $label['label_type']);
        
//         $response = [
//             'success' => true,
//             'data' => [
//                 'label' => $label,
//                 'packing' => $packing,
//                 'all_labels' => $all_labels,
//                 'next_actions' => $next_actions,
//                 'is_multi_label' => $label['label_type'] === 'master' || $label['label_type'] === 'child'
//             ]
//         ];
//     } else {
//         $response = [
//             'success' => false,
//             'message' => 'Label tidak ditemukan'
//         ];
//     }
    
//     $this->output
//         ->set_content_type('application/json')
//         ->set_output(json_encode($response));
// }

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
    
    $result = $this->Gudang_model->process_label_scan($label_id, $action, $user_id);
    
    if ($result) {
        // Get updated label info
        $label = $this->Gudang_model->get_label_by_code($label_code);
        
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
            $result = $this->Gudang_model->process_label_scan($label['id'], $action, $user_id);
            
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
    
    $stats = $this->Gudang_model->get_label_statistics($date);
    
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
        $labels = $this->Gudang_model->get_labels_by_packing($packing_id);
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
			'packing' => $this->Gudang_model->get_packing_detail($packing_id)
		];
		
		$this->load->view('template', $data);
	}

	/**
	 * API untuk generate label dengan format
	 */
	// 	public function api_generate_label_format() {
	// 	try {
	// 		// Enable error reporting untuk debugging
	// 		error_reporting(E_ALL);
	// 		ini_set('display_errors', 1);
			
	// 		$post = $this->input->post();
			
	// 		// Log input untuk debugging
	// 		log_message('debug', 'API Generate Format - POST Data: ' . print_r($post, true));
			
	// 		// Validasi input
	// 		if (empty($post['packing_id'])) {
	// 			throw new Exception('Packing ID diperlukan');
	// 		}
			
	// 		$packing_id = (int)$post['packing_id'];
	// 		$label_count = isset($post['label_count']) ? (int)$post['label_count'] : 1;
	// 		$label_format = $post['label_format'] ?? 'kenda';
	// 		$label_type = $post['label_type'] ?? 'single';
			
	// 		// Validasi nilai
	// 		if ($packing_id <= 0) {
	// 			throw new Exception('Packing ID tidak valid');
	// 		}
			
	// 		if ($label_count < 1) {
	// 			$label_count = 1;
	// 		}
			
	// 		// Cek apakah packing exists
	// 		$this->db->where('id', $packing_id);
	// 		$packing = $this->db->get('packing_list')->row();
			
	// 		if (!$packing) {
	// 			throw new Exception("Packing list #{$packing_id} tidak ditemukan");
	// 		}
			
	// 		// Panggil model dengan try-catch
	// 		$labels = $this->Gudang_model->generate_labels_with_format($packing_id, $label_count, $label_format, $label_type);
			
	// 		if ($labels === false) {
	// 			throw new Exception('Gagal generate label. Silakan cek log server.');
	// 		}
			
	// 		$response = [
	// 			'success' => true,
	// 			'message' => 'Label berhasil digenerate',
	// 			'data' => [
	// 				'labels' => $labels,
	// 				'total_labels' => count($labels),
	// 				'packing_id' => $packing_id,
	// 				'format' => $label_format
	// 			]
	// 		];
			
	// 	} catch (Exception $e) {
	// 		$response = [
	// 			'success' => false,
	// 			'message' => 'Error: ' . $e->getMessage(),
	// 			'debug_info' => [
	// 				'file' => $e->getFile(),
	// 				'line' => $e->getLine(),
	// 				'trace' => $e->getTraceAsString()
	// 			]
	// 		];
			
	// 		// Log error
	// 		log_message('error', 'API Generate Label Format Error: ' . $e->getMessage());
	// 		log_message('error', 'Trace: ' . $e->getTraceAsString());
	// 	}
		
	// 	// Pastikan output JSON
	// 	$this->output
	// 		->set_content_type('application/json')
	// 		->set_output(json_encode($response, JSON_PRETTY_PRINT));
	// }
	/**
	 * Cetak label dengan format tertentu
	 */
	public function cetak_label_format($label_id, $format = 'kenda') {
		// Update label status menjadi printed
		$this->Gudang_model->update_label_printed($label_id);
		
		$label_data = $this->get_label_data_for_print($label_id, $format);
		
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
	 * Cetak multiple labels sekaligus
	 */
	public function cetak_multiple_labels_format() {
		$label_ids = $this->input->get('ids');
		$format = $this->input->get('format') ?? 'kenda';
		
		if (empty($label_ids)) {
			show_error('Tidak ada label yang dipilih');
		}
		
		$ids_array = explode(',', $label_ids);
		$labels_data = [];
		
		foreach ($ids_array as $label_id) {
			// Update status ke printed
			$this->Gudang_model->update_label_printed($label_id);
			
			$label_data = $this->get_label_data_for_print($label_id, $format);
			if ($label_data) {
				$labels_data[] = $label_data;
			}
		}
		
		if (empty($labels_data)) {
			show_error('Tidak ada data label yang valid');
		}
		
		$data = [
			'labels_data' => $labels_data,
			'format' => $format,
			'print_time' => date('Y-m-d H:i:s'),
			'total_labels' => count($labels_data)
		];
		
		// Load view berdasarkan format
		$view_name = "packing list/cetak_multiple_labels_{$format}";
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
			'packing' => $this->Gudang_model->get_packing_detail($packing_id),
			'labels' => $this->Gudang_model->get_labels_by_packing_id($packing_id)
		];
		
		$this->load->view('template', $data);
	}


	public function cetak_single_label($label_id, $format = 'kenda') {
    $this->load->model('Gudang_model');
    
    // Validasi label
    $this->db->where('id', $label_id);
    $label = $this->db->get('labels')->row_array();
    
    if (!$label) {
        show_error('Label tidak ditemukan');
    }
    
    // Update status label
    $this->db->where('id', $label_id);
    $this->db->update('labels', [
        'status' => 'printed',
        'printed_at' => date('Y-m-d H:i:s')
    ]);
    
    // Get data untuk cetak
    $label_data = $this->get_label_data_for_print($label_id, $format);
    
    if (!$label_data) {
        show_error('Data label tidak ditemukan');
    }
    
    $data = [
        'label_data' => $label_data,
        'format' => $format,
        'print_time' => date('Y-m-d H:i:s')
    ];
    
    // Tentukan view berdasarkan format
    $view_name = "label/cetak_{$format}";
    $this->load->view($view_name, $data);
}

/**
 * Cetak multiple labels
 */
// Di Gudang.php - perbaiki method cetak_multiple_labels()
// public function cetak_multiple_labels() {
//     try {
//         // Get IDs from query string
//         $ids = $this->input->get('ids');
//         $format = $this->input->get('format') ?: 'kenda';
        
//         if (empty($ids)) {
//             show_error('Tidak ada label yang dipilih untuk dicetak');
//             return;
//         }
        
//         // Convert string to array
//         $ids_array = explode(',', $ids);
//         $ids_array = array_map('intval', $ids_array);
//         $ids_array = array_filter($ids_array);
        
//         if (empty($ids_array)) {
//             show_error('Tidak ada ID label yang valid');
//             return;
//         }
        
//         // Get labels data
//         $this->db->where_in('id', $ids_array);
//         $labels = $this->db->get('labels')->result_array();
        
//         if (empty($labels)) {
//             show_error('Label tidak ditemukan');
//             return;
//         }
        
//         // Update status semua label ke printed
//         $this->db->where_in('id', $ids_array);
//         $this->db->update('labels', [
//             'status' => 'printed',
//             'printed_at' => date('Y-m-d H:i:s')
//         ]);
        
//         // Prepare data untuk view
//         $labels_data = [];
//         foreach ($labels as $label) {
//             $label_data = $this->get_label_data_for_print($label['id'], $format);
//             if ($label_data) {
//                 $labels_data[] = $label_data;
//             }
//         }
        
//         $data = [
//             'labels_data' => $labels_data,
//             'format' => $format,
//             'total_labels' => count($labels_data),
//             'print_time' => date('Y-m-d H:i:s')
//         ];
        
//         // Load view berdasarkan format
//         $view_name = "packing list/cetak_multiple_labels_kenda"; // Pastikan view ini ada
        
//         // Debug info (hapus di production)
//         $data['debug_info'] = [
//             'ids_input' => $ids,
//             'ids_array' => $ids_array,
//             'count' => count($labels),
//             'format' => $format
//         ];
        
//         $this->load->view($view_name, $data);
        
//     } catch (Exception $e) {
//         show_error('Terjadi kesalahan: ' . $e->getMessage());
//     }
// }

		public function cetak_multiple_labels() {
    try {
        // Get IDs from query string
        $ids = $this->input->get('ids');
        $format = $this->input->get('format') ?: 'kenda';
        
        if (empty($ids)) {
            show_error('Tidak ada label yang dipilih untuk dicetak');
            return;
        }
        
        // Convert string to array
        $ids_array = explode(',', $ids);
        $ids_array = array_map('intval', $ids_array);
        $ids_array = array_filter($ids_array);
        
        if (empty($ids_array)) {
            show_error('Tidak ada ID label yang valid');
            return;
        }
        
        // Get labels data
        $this->db->where_in('id', $ids_array);
        $labels = $this->db->get('labels')->result_array();
        
        if (empty($labels)) {
            show_error('Label tidak ditemukan');
            return;
        }
        
        // Update status semua label ke printed
        $this->db->where_in('id', $ids_array);
        $this->db->update('labels', [
            'status' => 'printed',
            'printed_at' => date('Y-m-d H:i:s')
        ]);
        
        // Prepare data untuk view
        $labels_data = [];
        foreach ($labels as $label) {
            $label_data = $this->get_label_data_for_print($label['id'], $format);
            if ($label_data) {
                $labels_data[] = $label_data;
            }
        }
        
        // Tambahkan ini untuk debug
        // echo "<pre>"; print_r($labels_data); echo "</pre>"; die();
        
        $data = [
            'labels_data' => $labels_data,
            'format' => $format,
            'total_labels' => count($labels_data),
            'print_time' => date('Y-m-d H:i:s')
        ];
        
        // Load view berdasarkan format
        $view_name = "packing list/cetak_multiple_labels_{$format}";
        
        // Debug info (hapus di production)
        // $data['debug_info'] = [
        //     'ids_input' => $ids,
        //     'ids_array' => $ids_array,
        //     'count' => count($labels),
        //     'format' => $format,
        //     'labels_data_sample' => $labels_data[0] ?? 'no data'
        // ];
        
        $this->load->view($view_name, $data);
        
    } catch (Exception $e) {
        show_error('Terjadi kesalahan: ' . $e->getMessage());
    }
}

// Helper method untuk get label data
private function get_label_data_for_print($label_id, $format = 'kenda') {
    $this->db->where('id', $label_id);
    $label = $this->db->get('labels')->row_array();
    
    if (!$label) {
        return false;
    }
    
    // Get packing data dengan semua field yang diperlukan
    $this->db->where('id', $label['packing_id']);
    $packing = $this->db->get('packing_list')->row_array();
    
    if (!$packing) {
        return false;
    }
    
    // Get items dengan semua data dari database
    $this->db->select('pi.*, b.*');
    $this->db->from('packing_items pi');
    $this->db->join('barang b', 'pi.kode_barang = b.kode_barang', 'left');
    $this->db->where('pi.packing_id', $label['packing_id']);
    $items = $this->db->get()->result_array();
    
    // Hitung total quantity dari items
    $total_qty = 0;
    foreach ($items as $item) {
        $total_qty += (int)$item['qty'];
    }
    
    // Format data untuk label
    $formatted_data = [
        'label' => $label,
        'packing' => $packing,
        'items' => $items,
        'format' => $format,
        'total_qty' => $total_qty,
        'item_count' => count($items)
    ];
    
    // Tambahkan data spesifik berdasarkan format
    switch ($format) {
        case 'kenda':
            $formatted_data = array_merge($formatted_data, $this->_format_kenda_label($packing, $items, $label));
            break;
        case 'xds':
            $formatted_data = array_merge($formatted_data, $this->_format_xds_label($packing, $items, $label));
            break;
        case 'btg':
            $formatted_data = array_merge($formatted_data, $this->_format_btg_label($packing, $items, $label));
            break;
        case 'standard':
            $formatted_data = array_merge($formatted_data, $this->_format_standard_label($packing, $items, $label));
            break;
    }
    
    return $formatted_data;
}

// Tambahkan method format helper di controller jika belum ada
private function _format_kenda_label($packing, $items, $label) {
    $first_item = $items[0] ?? [];
    
    // Ambil data dari database packing atau items
    $po_no = !empty($packing['po_no']) ? $packing['po_no'] : 
             (!empty($first_item['no_po']) ? $first_item['no_po'] : 'N/A');
    
    return [
        'company_name' => 'PT. KENDA RUBBER INDONESIA',
        'po_no' => $po_no,
        'order_qty' => $packing['jumlah_item'] ?? 0,
        'bales_no' => isset($label['bale_number']) ? 
                     "{$label['bale_number']}/{$label['total_bales']}" : '1/1',
        'qty_per_bale' => $first_item['qty'] ?? 
                         (isset($packing['jumlah_item']) ? $packing['jumlah_item'] : 0),
        'nw' => !empty($packing['net_weight']) ? $packing['net_weight'] : 
                (!empty($first_item['net_weight']) ? $first_item['net_weight'] : '0.00'),
        'gw' => !empty($packing['gross_weight']) ? $packing['gross_weight'] : 
                (!empty($first_item['gross_weight']) ? $first_item['gross_weight'] : '0.00'),
        'item_code' => $first_item['kode_barang'] ?? 'N/A',
        'cfr' => !empty($packing['cfr']) ? $packing['cfr'] : 
                (!empty($first_item['cfr']) ? $first_item['cfr'] : 'N/A'),
        'description' => $first_item['nama_barang'] ?? 
                        (!empty($packing['description']) ? $packing['description'] : 'Product'),
        'made_in' => 'MADE IN INDONESIA'
    ];
}

// Tambahkan method untuk format XDS yang benar
private function _format_xds_label($packing, $items, $label) {
    $first_item = $items[0] ?? [];
    
    return [
        'company_name' => 'XDS BICYCLE CAMBODIA',
        'product_name' => 'BICYCLE TIRE',
        'po_no' => !empty($packing['po_no']) ? $packing['po_no'] : 
                  (!empty($first_item['no_po']) ? $first_item['no_po'] : 'N/A'),
        'size' => !empty($packing['size']) ? $packing['size'] : 
                 (!empty($first_item['size']) ? $first_item['size'] : 'N/A'),
        'kenda_size' => !empty($packing['kenda_size']) ? $packing['kenda_size'] : 
                       (!empty($first_item['kenda_size']) ? $first_item['kenda_size'] : 'N/A'),
        'item_no' => $first_item['kode_barang'] ?? 'N/A',
        'pkg_no' => $packing['no_packing'] ?? '',
        'qty' => $packing['jumlah_item'] ?? 0,
        'nw' => !empty($packing['net_weight']) ? $packing['net_weight'] : 
                (!empty($first_item['net_weight']) ? $first_item['net_weight'] : '0.00'),
        'gw' => !empty($packing['gross_weight']) ? $packing['gross_weight'] : 
                (!empty($first_item['gross_weight']) ? $first_item['gross_weight'] : '0.00'),
        'made_in' => 'MADE IN INDONESIA'
    ];
}

/**
 * Format untuk label BTG (sesuai gambar 3)
 */
private function _format_btg_label($packing, $items) {
    $first_item = $items[0] ?? [];
    
    return [
        'company_name' => 'BIG PACTUAL COMMODITIES SERTRADING S.A',
        'vendor_name' => 'KENDA RUBBER',
        'po_no' => $first_item['no_po'] ?? '6586-4',
        'codigo' => $first_item['codigo'] ?? '59338',
        'product_name' => 'PNEU PUBLICICLETA',
        'size' => $first_item['size'] ?? '26*1.95 K1300 BK',
        'color' => $first_item['color'] ?? '26*1.95 K1300 PRETO',
        'qty' => $first_item['qty'] ?? 25,
        'nw' => $first_item['net_weight'] ?? '18.75',
        'gw' => $first_item['gross_weight'] ?? '18.95',
        'made_in' => 'MADE IN INDONESIA',
        'import_address' => 'BTG PACTUAL COMMODITIES SERTRADING S.A ROD. GOVERNADOR MARIO COVAS 3101, KM 282. AREA 4,QUADRA 2; PADRE MATHIAS – CARIACICA – ZIP CODE: 29157-100 – ESPIRITO SANTO/ES.BRAZIL',
        'cnpj' => '04.626.426/0007-00'
    ];
}

/**
 * Format untuk label Standard (sesuai gambar 4)
 */
private function _format_standard_label($packing, $items) {
    $first_item = $items[0] ?? [];
    
    return [
        'vendor_name' => 'KENDA RUBBER',
        'part_no' => $first_item['part_no'] ?? '757745',
        'description' => $first_item['nama_barang'] ?? '700*28/32C R/V -22*28T 48L NI',
        'qty' => $first_item['qty'] ?? 50,
        'made_in' => 'MADE IN INDONESIA',
        'production_date' => date('Ymd'),
        'batch_info' => isset($packing['bale_number']) ? "{$packing['bale_number']} OF {$packing['total_bales']}" : "29 OF 44"
    ];
}

/**
 * Format default label
 */
private function _format_default_label($packing, $items) {
    return [
        'company_name' => 'PT. KENDA RUBBER INDONESIA',
        'packing_no' => $packing['no_packing'],
        'customer' => $packing['customer'],
        'date' => $packing['tanggal'],
        'total_items' => $packing['jumlah_item'],
        'made_in' => 'MADE IN INDONESIA'
    ];
}

	
}
