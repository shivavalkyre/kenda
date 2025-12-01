<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Gudang_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    // Dashboard
    public function index()
    {
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
        
        $this->load->view('template', $data);
    }

    // Laporan Stok
    public function stok()
    {
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
    public function barang()
    {
        $data = array(
            'title' => 'Data Barang - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'barang',
            'content' => 'barang/index',
            'total_barang' => $this->Gudang_model->get_total_barang(),
            'total_tube' => $this->Gudang_model->get_total_by_kategori('Tube'),
            'total_tire' => $this->Gudang_model->get_total_by_kategori('Tire'),
            'stok_minimum' => $this->Gudang_model->get_stok_minimum_count(),
            'barang_list' => $this->Gudang_model->get_all_barang()
        );
        
        $this->load->view('template', $data);
    }

    // Scan Label
    public function scan()
    {
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
    public function packing_list()
    {
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
    public function kategori()
    {
        $data = array(
            'title' => 'Kategori Barang - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'kategori',
            'content' => 'kategori/index',
            'statistics' => $this->Gudang_model->get_kategori_statistics()
        );
        
        $this->load->view('template', $data);
    }

    // API Methods untuk AJAX calls

    // API untuk Daftar Kategori
    public function api_list_kategori() {
        $kategori_list = $this->Gudang_model->get_kategori_list();
        
        $response = [
            'success' => true,
            'data' => $kategori_list
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // API untuk Statistics Kategori
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

    // API untuk Detail Kategori
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

    // Simpan Kategori
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

    // Update Kategori
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

    // Hapus Kategori
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

    // API untuk Packing List
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

    // API untuk Detail Packing List
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

	// ==================== BARANG METHODS ====================

// API untuk Detail Barang
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

// Tambah Barang
public function tambah_barang() {
    $this->load->library('form_validation');
    
    $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|is_unique[barang.kode_barang]');
    $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
    $this->form_validation->set_rules('kategori', 'Kategori', 'required');
    $this->form_validation->set_rules('satuan', 'Satuan', 'required');
    
    if ($this->form_validation->run() === FALSE) {
        $response = [
            'success' => false,
            'message' => validation_errors()
        ];
    } else {
        $data = [
            'kode_barang' => $this->input->post('kode_barang'),
            'nama_barang' => $this->input->post('nama_barang'),
            'kategori' => $this->input->post('kategori'),
            'satuan' => $this->input->post('satuan'),
            'stok_minimum' => $this->input->post('stok_minimum') ?: 0,
            'status' => $this->input->post('status') ?: 'aktif',
            'deskripsi' => $this->input->post('deskripsi'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Set stok awal ke 0, nanti diinput melalui stok awal
        $data['stok'] = 0;
        
        $result = $this->db->insert('barang', $data);
        
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
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

// Update Barang
public function update_barang() {
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
    
    $this->db->where('kode_barang', $kode_barang);
    $result = $this->db->update('barang', $data);
    
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
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

// Hapus Barang (soft delete)
public function hapus_barang($kode_barang) {
    $data = [
        'status' => 'nonaktif',
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $this->db->where('kode_barang', $kode_barang);
    $result = $this->db->update('barang', $data);
    
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
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

// Input Stok Awal
public function stok_awal() {
    $kode_barang = $this->input->post('kode_barang');
    $stok_awal = $this->input->post('stok_awal');
    
    // Update stok barang
    $this->db->where('kode_barang', $kode_barang);
    $result = $this->db->update('barang', [
        'stok' => $stok_awal,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        // Log stok awal
        $log_data = [
            'kode_barang' => $kode_barang,
            'jenis' => 'stok_awal',
            'qty' => $stok_awal,
            'keterangan' => $this->input->post('keterangan'),
            'tanggal' => $this->input->post('tanggal'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('log_stok', $log_data);
        
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
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

// Barang Masuk
public function barang_masuk() {
    $kode_barang = $this->input->post('kode_barang');
    $jumlah = $this->input->post('jumlah');
    
    // Get current stok
    $this->db->where('kode_barang', $kode_barang);
    $barang = $this->db->get('barang')->row_array();
    
    if (!$barang) {
        $response = [
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ];
    } else {
        $stok_baru = $barang['stok'] + $jumlah;
        
        // Update stok barang
        $this->db->where('kode_barang', $kode_barang);
        $result = $this->db->update('barang', [
            'stok' => $stok_baru,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            // Log barang masuk
            $log_data = [
                'kode_barang' => $kode_barang,
                'jenis' => 'masuk',
                'qty' => $jumlah,
                'supplier' => $this->input->post('supplier'),
                'no_po' => $this->input->post('no_po'),
                'keterangan' => $this->input->post('keterangan'),
                'tanggal' => $this->input->post('tanggal'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('log_stok', $log_data);
            
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
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

// Export Data Barang
public function export_barang() {
    // Implementation for export
    $this->load->helper('download');
    
    $data = $this->Gudang_model->get_all_barang();
    
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

// ==================== BARANG METHODS (Tambahan) ====================

public function count_all() {
    $this->db->where('status', 'aktif');
    return $this->db->count_all_results('barang');
}

public function count_by_kategori($kategori) {
    $this->db->where('kategori', $kategori);
    $this->db->where('status', 'aktif');
    return $this->db->count_all_results('barang');
}

public function count_stok_minimum() {
    $this->db->where('stok <= stok_minimum');
    $this->db->where('status', 'aktif');
    return $this->db->count_all_results('barang');
}

public function get_all() {
    $this->db->where('status', 'aktif');
    $this->db->order_by('nama_barang', 'ASC');
    return $this->db->get('barang')->result_array();
}

public function get_by_kode($kode_barang) {
    $this->db->where('kode_barang', $kode_barang);
    return $this->db->get('barang')->row_array();
}

public function insert($data) {
    return $this->db->insert('barang', $data);
}

public function update($kode_barang, $data) {
    $this->db->where('kode_barang', $kode_barang);
    return $this->db->update('barang', $data);
}

public function delete($kode_barang) {
    $this->db->where('kode_barang', $kode_barang);
    return $this->db->delete('barang');
}

public function insert_transaksi($data) {
    return $this->db->insert('transaksi_stok', $data);
}

// ==================== EKSPOR BARANG ====================

public function get_all_for_export() {
    $this->db->select('*');
    $this->db->from('barang');
    $this->db->where('status', 'aktif');
    $this->db->order_by('kategori', 'ASC');
    $this->db->order_by('nama_barang', 'ASC');
    return $this->db->get()->result_array();
}

    // API untuk Daftar Barang
    public function api_list_barang() {
        $barang = $this->Gudang_model->get_all_barang();
        
        $response = [
            'success' => true,
            'data' => $barang
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // API untuk Simpan Packing List
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

    // API untuk Update Packing List
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

    // API untuk Hapus Packing List
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

    // API untuk Scan Actions
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

    // API untuk Cetak Label
    public function api_cetak_label() {
        $packing_ids = $this->input->post('packing_ids');
        $type = $this->input->post('type');

        // Simulasi data label
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

    // API untuk Check Label (Scan)
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

    // API untuk Process Scan
    public function api_process_scan() {
        $packing_id = $this->input->post('packing_id');
        $action = $this->input->post('action');
        $label_code = $this->input->post('label_code');

        // Update status berdasarkan action
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

    // API untuk Today Scan Stats
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
