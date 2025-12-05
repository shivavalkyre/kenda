<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Kategori_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Data Barang
    public function index() {
        $page = $this->input->get('page') ?: 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = $this->input->get('search') ?: '';
        $filter = $this->input->get('filter') ?: 'all';
        
        $total_barang = $this->Barang_model->get_total_barang();
        $total_tube = $this->Barang_model->get_total_by_kategori('Tube');
        $total_tire = $this->Barang_model->get_total_by_kategori('Tire');
        $stok_minimum = $this->Barang_model->get_stok_minimum_count();
        
        // Get barang list with pagination
        $barang_list = $this->Barang_model->get_barang_paginated($limit, $offset, $search, $filter);
        $total_filtered = $this->Barang_model->count_barang($search, $filter);
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

    // ==================== BARANG API METHODS ====================

    /**
     * API untuk Detail Barang
     */
    public function api_detail_barang($kode_barang) {
        $barang = $this->Barang_model->get_barang_detail($kode_barang);
        
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
        $barang_list = $this->Barang_model->get_all_barang();
        
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
            if ($this->Barang_model->is_kode_barang_exist($kode_barang)) {
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
                
                $result = $this->Barang_model->tambah_barang($data);
                
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
            
            $result = $this->Barang_model->update_barang($kode_barang, $data);
            
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
            $result = $this->Barang_model->hapus_barang($kode_barang);
            
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
            $result = $this->Barang_model->update_stok_barang($kode_barang, $stok_awal);
            
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
                $this->Barang_model->add_stok_log($log_data);
                
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
            $barang = $this->Barang_model->get_barang_detail($kode_barang);
            
            if (!$barang) {
                $response = [
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ];
            } else {
                $stok_baru = $barang['stok'] + $jumlah;
                
                // Update stok barang
                $result = $this->Barang_model->update_stok_barang($kode_barang, $stok_baru);
                
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
                    $this->Barang_model->add_stok_log($log_data);
                    
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
            $barang = $this->Barang_model->get_barang_detail($kode_barang);
            
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
                $result = $this->Barang_model->update_stok_barang($kode_barang, $stok_baru);
                
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
                    $this->Barang_model->add_stok_log($log_data);
                    
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
            $barang = $this->Barang_model->get_barang_detail($kode_barang);
            
            if (!$barang) {
                $response = [
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ];
            } else {
                $selisih = $stok_baru - $barang['stok'];
                
                // Update stok barang
                $result = $this->Barang_model->update_stok_barang($kode_barang, $stok_baru);
                
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
                    $this->Barang_model->add_stok_log($log_data);
                    
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
        
        $data = $this->Barang_model->get_barang_for_export();
        
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
        $statistics = $this->Barang_model->get_barang_statistics();
        
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
        
        $result = $this->Barang_model->get_barang_paginated($limit, $offset, $search, $filter);
        $total = $this->Barang_model->count_barang($search, $filter);
        
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

    /**
     * API untuk Get Barang untuk Packing List
     */
    public function api_barang_for_packing() {
        $barang_list = $this->Barang_model->get_barang_for_dropdown();
        
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
}
?>
