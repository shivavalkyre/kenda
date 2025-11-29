<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // Load model
        // $this->load->model('Kategori_model');
        
        // Check login
        if(!$this->session->userdata('username')) {
            redirect('auth/login');
        }
    }
    
    public function index()
    {
        $data = array(
            'title' => 'Kategori Barang - KENDA',
            'username' => $this->session->userdata('username'),
            'active_menu' => 'kategori',
            'content' => 'kategori/index'
        );
        
        $this->load->view('template', $data);
    }

    // API untuk mendapatkan data kategori
    public function get_kategori()
    {
        $this->output->set_content_type('application/json');
        
        // Sample data - dalam implementasi real, ambil dari database
        $kategori_list = [
            [
                'id' => 1,
                'kode_kategori' => 'TUB',
                'nama_kategori' => 'Tube',
                'deskripsi' => 'Kategori untuk berbagai jenis tube ban',
                'jumlah_barang' => 856,
                'status' => 'active',
                'created_at' => '2024-01-15 10:30:00'
            ],
            [
                'id' => 2,
                'kode_kategori' => 'TIR',
                'nama_kategori' => 'Tire',
                'deskripsi' => 'Kategori untuk berbagai jenis tire/ban',
                'jumlah_barang' => 392,
                'status' => 'active',
                'created_at' => '2024-01-15 10:30:00'
            ],
            [
                'id' => 3,
                'kode_kategori' => 'ACC',
                'nama_kategori' => 'Accessories',
                'deskripsi' => 'Kategori untuk aksesoris kendaraan',
                'jumlah_barang' => 125,
                'status' => 'active',
                'created_at' => '2024-02-10 14:20:00'
            ],
            [
                'id' => 4,
                'kode_kategori' => 'SPR',
                'nama_kategori' => 'Spare Part',
                'deskripsi' => 'Kategori untuk spare part kendaraan',
                'jumlah_barang' => 89,
                'status' => 'inactive',
                'created_at' => '2024-02-20 09:15:00'
            ]
        ];

        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $kategori_list,
            'total' => count($kategori_list)
        ]));
    }

    // Simpan kategori baru
    public function simpan()
    {
        $this->output->set_content_type('application/json');
        
        // Get POST data
        $kode_kategori = $this->input->post('kode_kategori');
        $nama_kategori = $this->input->post('nama_kategori');
        $deskripsi = $this->input->post('deskripsi');
        $status = $this->input->post('status');
        
        // Validasi input
        if (empty($kode_kategori) || empty($nama_kategori)) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Kode dan Nama Kategori harus diisi'
            ]));
            return;
        }
        
        // Check if kode kategori already exists (dalam implementasi real)
        // $existing = $this->Kategori_model->check_kode_exists($kode_kategori);
        
        // Simpan ke database (dalam implementasi real)
        // $kategori_id = $this->Kategori_model->simpan_kategori($data);
        
        // Simulasi penyimpanan berhasil
        $kategori_id = rand(100, 999);
        
        $this->output->set_output(json_encode([
            'success' => true,
            'message' => 'Kategori berhasil disimpan',
            'data' => [
                'id' => $kategori_id,
                'kode_kategori' => $kode_kategori,
                'nama_kategori' => $nama_kategori
            ]
        ]));
    }

    // Update kategori
    public function update()
    {
        $this->output->set_content_type('application/json');
        
        $id = $this->input->post('id');
        $kode_kategori = $this->input->post('kode_kategori');
        $nama_kategori = $this->input->post('nama_kategori');
        $deskripsi = $this->input->post('deskripsi');
        $status = $this->input->post('status');
        
        // Validasi
        if (empty($id) || empty($kode_kategori) || empty($nama_kategori)) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'ID, Kode, dan Nama Kategori harus diisi'
            ]));
            return;
        }
        
        // Update di database (dalam implementasi real)
        // $this->Kategori_model->update_kategori($id, $data);
        
        $this->output->set_output(json_encode([
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => [
                'id' => $id,
                'kode_kategori' => $kode_kategori,
                'nama_kategori' => $nama_kategori
            ]
        ]));
    }

    // Hapus kategori
    public function hapus($id)
    {
        $this->output->set_content_type('application/json');
        
        // Validasi
        if (empty($id)) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'ID kategori tidak valid'
            ]));
            return;
        }
        
        // Check if kategori has barang (dalam implementasi real)
        // $has_barang = $this->Kategori_model->check_has_barang($id);
        
        // Hapus dari database (dalam implementasi real)
        // $this->Kategori_model->hapus_kategori($id);
        
        $this->output->set_output(json_encode([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]));
    }

    // Get detail kategori
    public function detail($id)
    {
        $this->output->set_content_type('application/json');
        
        // Sample data - dalam implementasi real, ambil dari database
        $kategori_data = [
            'id' => $id,
            'kode_kategori' => 'TUB',
            'nama_kategori' => 'Tube',
            'deskripsi' => 'Kategori untuk berbagai jenis tube ban',
            'status' => 'active',
            'created_at' => '2024-01-15 10:30:00',
            'updated_at' => '2024-03-20 14:25:00',
            'jumlah_barang' => 856,
            'barang_terbaru' => [
                ['kode' => 'TUB001', 'nama' => 'Tube Standard 17"'],
                ['kode' => 'TUB002', 'nama' => 'Tube Heavy Duty 19"'],
                ['kode' => 'TUB003', 'nama' => 'Tube Racing 15"']
            ]
        ];
        
        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $kategori_data
        ]));
    }

    // Get statistics
    public function get_statistics()
    {
        $this->output->set_content_type('application/json');
        
        // Sample statistics - dalam implementasi real, hitung dari database
        $statistics = [
            'total_kategori' => 4,
            'active_kategori' => 3,
            'inactive_kategori' => 1,
            'total_barang' => 1462,
            'kategori_terbanyak' => 'Tube (856 barang)'
        ];
        
        $this->output->set_output(json_encode([
            'success' => true,
            'data' => $statistics
        ]));
    }
}