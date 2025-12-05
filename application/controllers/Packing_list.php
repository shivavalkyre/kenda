<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Packing_list extends CI_Controller {
    
//     public function __construct()
//     {
//         parent::__construct();
//         // Load model jika diperlukan
//         // $this->load->model('Packing_model');
//         // $this->load->model('Barang_model');
        
//         // Check login
//         if(!$this->session->userdata('username')) {
//             redirect('auth/login');
//         }
//     }
    
//     public function index()
//     {
//         // Data statistik
//         $data = array(
//             'title' => 'Packing List - KENDA',
//             'username' => $this->session->userdata('username'),
//             'active_menu' => 'packing',
//             'content' => 'packing list/index',
//             'total_packing' => 15,
//             'pending_packing' => 3,
//             'completed_packing' => 12,
//             'total_items' => 485
//         );
        
//         $this->load->view('template', $data);
//     }

//     // API untuk mendapatkan data packing list
//     public function get_packing_list()
//     {
//         $this->output->set_content_type('application/json');
        
//         $filter_status = $this->input->get('status');
        
//         // Sample data - dalam implementasi real, ambil dari database
//         $packing_list = [
//             [
//                 'id' => 1,
//                 'no_packing' => 'PL001',
//                 'tanggal' => '2024-03-20',
//                 'customer' => 'Customer A',
//                 'jumlah_item' => 50,
//                 'status_label' => 'printed',
//                 'status_loading' => 'loaded',
//                 'items' => [
//                     ['kode' => 'TIR001', 'nama' => 'Tire A', 'qty' => 30],
//                     ['kode' => 'TUB001', 'nama' => 'Tube B', 'qty' => 20]
//                 ]
//             ],
//             [
//                 'id' => 2,
//                 'no_packing' => 'PL002',
//                 'tanggal' => '2024-03-20',
//                 'customer' => 'Customer B',
//                 'jumlah_item' => 30,
//                 'status_label' => 'printed',
//                 'status_loading' => 'pending',
//                 'items' => [
//                     ['kode' => 'TUB002', 'nama' => 'Tube C', 'qty' => 30]
//                 ]
//             ],
//             [
//                 'id' => 3,
//                 'no_packing' => 'PL003',
//                 'tanggal' => '2024-03-19',
//                 'customer' => 'Customer C',
//                 'jumlah_item' => 25,
//                 'status_label' => 'draft',
//                 'status_loading' => 'pending',
//                 'items' => [
//                     ['kode' => 'TIR002', 'nama' => 'Tire D', 'qty' => 25]
//                 ]
//             ]
//         ];

//         // Filter data jika diperlukan
//         if ($filter_status && $filter_status !== 'all') {
//             $packing_list = array_filter($packing_list, function($item) use ($filter_status) {
//                 if ($filter_status === 'draft' || $filter_status === 'printed' || $filter_status === 'scanned') {
//                     return $item['status_label'] === $filter_status;
//                 } elseif ($filter_status === 'loaded') {
//                     return $item['status_loading'] === 'loaded';
//                 }
//                 return true;
//             });
//         }

//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => array_values($packing_list)
//         ]));
//     }

//     // Simpan packing list baru
//     public function simpan()
//     {
//         $this->output->set_content_type('application/json');
        
//         // Get POST data
//         $no_packing = $this->input->post('no_packing');
//         $tanggal = $this->input->post('tanggal');
//         $customer = $this->input->post('customer');
//         $alamat = $this->input->post('alamat');
//         $keterangan = $this->input->post('keterangan');
//         $items = $this->input->post('items');
        
//         // Validasi input
//         if (empty($no_packing) || empty($tanggal) || empty($customer)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'No. Packing, Tanggal, dan Customer harus diisi'
//             ]));
//             return;
//         }
        
//         if (empty($items)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'Minimal satu item harus ditambahkan'
//             ]));
//             return;
//         }
        
//         // Simpan ke database (dalam implementasi real)
//         // $packing_id = $this->Packing_model->simpan_packing($data);
        
//         // Simulasi penyimpanan berhasil
//         $packing_id = rand(100, 999);
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'message' => 'Packing list berhasil disimpan',
//             'data' => [
//                 'id' => $packing_id,
//                 'no_packing' => $no_packing,
//                 'redirect_url' => site_url('packing_list')
//             ]
//         ]));
//     }

//     // Detail packing list
//     public function detail($id)
//     {
//         $this->output->set_content_type('application/json');
        
//         // Sample data - dalam implementasi real, ambil dari database
//         $packing_data = [
//             'id' => $id,
//             'no_packing' => 'PL' . str_pad($id, 3, '0', STR_PAD_LEFT),
//             'tanggal' => '2024-03-20',
//             'customer' => 'Customer ' . chr(64 + $id),
//             'alamat' => 'Alamat customer ' . chr(64 + $id),
//             'keterangan' => 'Keterangan packing list',
//             'status_label' => 'printed',
//             'status_loading' => 'pending',
//             'items' => [
//                 [
//                     'kode' => 'TIR001',
//                     'nama' => 'Tire Radial 205/55/R16',
//                     'kategori' => 'Tire',
//                     'qty' => 30
//                 ],
//                 [
//                     'kode' => 'TUB001',
//                     'nama' => 'Tube Standard 17"',
//                     'kategori' => 'Tube',
//                     'qty' => 20
//                 ]
//             ],
//             'total_items' => 50
//         ];
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => $packing_data
//         ]));
//     }

//     // Cetak label packing
//     public function cetak_label()
//     {
//         $this->output->set_content_type('application/json');
        
//         $packing_ids = $this->input->post('packing_ids');
//         $type = $this->input->post('type'); // single atau multiple
        
//         if (empty($packing_ids)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'Pilih packing list yang akan dicetak'
//             ]));
//             return;
//         }
        
//         // Generate label data (dalam implementasi real)
//         $label_data = [];
//         foreach ($packing_ids as $packing_id) {
//             $label_data[] = [
//                 'packing_id' => $packing_id,
//                 'no_packing' => 'PL' . str_pad($packing_id, 3, '0', STR_PAD_LEFT),
//                 'customer' => 'Customer ' . chr(64 + $packing_id),
//                 'tanggal' => date('Y-m-d'),
//                 'barcode' => 'PL' . str_pad($packing_id, 3, '0', STR_PAD_LEFT) . '-' . date('Ymd'),
//                 'items' => [
//                     ['nama' => 'Tire A', 'qty' => 10],
//                     ['nama' => 'Tube B', 'qty' => 5]
//                 ]
//             ];
//         }
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'message' => 'Label berhasil digenerate',
//             'data' => [
//                 'labels' => $label_data,
//                 'total' => count($label_data)
//             ]
//         ]));
//     }

//     // Update status packing
//     public function update_status()
//     {
//         $this->output->set_content_type('application/json');
        
//         $packing_id = $this->input->post('packing_id');
//         $field = $this->input->post('field'); // status_label atau status_loading
//         $value = $this->input->post('value');
        
//         // Validasi
//         $valid_fields = ['status_label', 'status_loading'];
//         $valid_label_values = ['draft', 'printed', 'scanned'];
//         $valid_loading_values = ['pending', 'loading', 'loaded'];
        
//         if (!in_array($field, $valid_fields)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'Field status tidak valid'
//             ]));
//             return;
//         }
        
//         if ($field === 'status_label' && !in_array($value, $valid_label_values)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'Nilai status label tidak valid'
//             ]));
//             return;
//         }
        
//         if ($field === 'status_loading' && !in_array($value, $valid_loading_values)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'Nilai status loading tidak valid'
//             ]));
//             return;
//         }
        
//         // Update di database (dalam implementasi real)
//         // $this->Packing_model->update_status($packing_id, $field, $value);
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'message' => 'Status berhasil diupdate',
//             'data' => [
//                 'packing_id' => $packing_id,
//                 'field' => $field,
//                 'value' => $value
//             ]
//         ]));
//     }

//     // Get data barang untuk dropdown
//     public function get_barang_list()
//     {
//         $this->output->set_content_type('application/json');
        
//         // Sample data barang - dalam implementasi real, ambil dari database
//         $barang_list = [
//             [
//                 'id' => 1,
//                 'kode' => 'TUB001',
//                 'nama' => 'Tube Standard 17"',
//                 'kategori' => 'Tube',
//                 'stok' => 100
//             ],
//             [
//                 'id' => 2,
//                 'kode' => 'TIR001',
//                 'nama' => 'Tire Radial 205/55/R16',
//                 'kategori' => 'Tire',
//                 'stok' => 50
//             ],
//             [
//                 'id' => 3,
//                 'kode' => 'TUB002',
//                 'nama' => 'Tube Heavy Duty 19"',
//                 'kategori' => 'Tube',
//                 'stok' => 30
//             ],
//             [
//                 'id' => 4,
//                 'kode' => 'TIR002',
//                 'nama' => 'Tire Offroad 265/70/R16',
//                 'kategori' => 'Tire',
//                 'stok' => 20
//             ],
//             [
//                 'id' => 5,
//                 'kode' => 'TUB003',
//                 'nama' => 'Tube Racing 15"',
//                 'kategori' => 'Tube',
//                 'stok' => 40
//             ]
//         ];
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => $barang_list
//         ]));
//     }

//     // Get statistics
//     public function get_statistics()
//     {
//         $this->output->set_content_type('application/json');
        
//         // Sample statistics - dalam implementasi real, hitung dari database
//         $statistics = [
//             'total_packing' => 15,
//             'pending_packing' => 3,
//             'completed_packing' => 12,
//             'total_items' => 485,
//             'today_packing' => 2,
//             'week_packing' => 8
//         ];
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'data' => $statistics
//         ]));
//     }

//     // Hapus packing list
//     public function hapus($id)
//     {
//         $this->output->set_content_type('application/json');
        
//         // Validasi
//         if (empty($id)) {
//             $this->output->set_output(json_encode([
//                 'success' => false,
//                 'message' => 'ID packing list tidak valid'
//             ]));
//             return;
//         }
        
//         // Hapus dari database (dalam implementasi real)
//         // $this->Packing_model->hapus_packing($id);
        
//         $this->output->set_output(json_encode([
//             'success' => true,
//             'message' => 'Packing list berhasil dihapus'
//         ]));
//     }

//     // Export packing list (PDF/Excel)
//     public function export()
//     {
//         $type = $this->input->get('type'); // pdf atau excel
//         $packing_id = $this->input->get('packing_id');
        
//         // Load library untuk export
//         // $this->load->library('pdf');
//         // atau
//         // $this->load->library('excel');
        
//         if ($type === 'pdf') {
//             // Generate PDF
//             // $this->pdf->generate_packing_pdf($packing_id);
//             echo "PDF Export untuk packing list {$packing_id}";
//         } elseif ($type === 'excel') {
//             // Generate Excel
//             // $this->excel->generate_packing_excel($packing_id);
//             echo "Excel Export untuk packing list {$packing_id}";
//         } else {
//             echo "Format export tidak didukung";
//         }
//     }
// }


class Packing_list extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Packing_model');
        $this->load->model('Barang_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Packing List
    public function index() {
        $data = array(
            'title' => 'Packing List - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'packing',
            'content' => 'packing list/index',
            'total_packing' => $this->Packing_model->get_total_packing(),
            'pending_packing' => $this->Packing_model->get_pending_packing(),
            'completed_packing' => $this->Packing_model->get_completed_packing(),
            'total_items' => $this->Packing_model->get_total_items_packed()
        );
        
        $this->load->view('template', $data);
    }

    // ==================== PACKING LIST API METHODS ====================

    public function api_list_packing() {
        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        $filter = $this->input->get('filter') ?: 'all';
        $search = $this->input->get('search') ?: '';

        $offset = ($page - 1) * $limit;

        $result = $this->Packing_model->get_packing_list($limit, $offset, $filter, $search);
        $total = $this->Packing_model->count_packing_list($filter, $search);

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
        $packing = $this->Packing_model->get_packing_detail($id);
        
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

        $result = $this->Packing_model->save_packing_list($data, $items);

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

        $result = $this->Packing_model->update_packing_list($packing_id, $data, $items);

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
        $result = $this->Packing_model->delete_packing_list($id);

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

    // ==================== PACKING CETAK METHODS ====================

    public function cetak_packing($id) {
        // Ambil data dari model
        $packing = $this->Packing_model->get_packing_detail($id);
        
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

    public function cetak_label($id = null) {
        // Ambil data packing list
        $data['packing'] = $this->Packing_model->getPackingById($id);
        
        // Load view cetak label
        $this->load->view('packing list/cetak_label', $data);
    }

    public function cetak_label_multiple() {
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
                // Get packing lists data
                $packing_lists = $this->Packing_model->get_packing_by_ids($ids_array);
                
                if (empty($packing_lists)) {
                    // Jika tidak ada data, coba query sederhana
                    $packing_lists = $this->Packing_model->getPackingByIds($ids_array);
                    
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

    // Update status packing
    public function api_update_status() {
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
        
        $result = $this->Packing_model->updatePackingStatus($packing_id, $status);
        
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

    // Cetak label dengan format tertentu
    public function cetak_single_label($label_id, $format = 'kenda') {
        $this->load->model('Label_model');
        
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
        $label_data = $this->Label_model->get_label_data_for_print($label_id, $format);
        
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
            
            // Load model label
            $this->load->model('Label_model');
            
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
                $label_data = $this->Label_model->get_label_data_for_print($label['id'], $format);
                if ($label_data) {
                    $labels_data[] = $label_data;
                }
            }
            
            $data = [
                'labels_data' => $labels_data,
                'format' => $format,
                'total_labels' => count($labels_data),
                'print_time' => date('Y-m-d H:i:s')
            ];
            
            // Load view berdasarkan format
            $view_name = "packing list/cetak_multiple_labels_{$format}";
            $this->load->view($view_name, $data);
            
        } catch (Exception $e) {
            show_error('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

