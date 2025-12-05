<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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

    public function get_dashboard_stats() {
        $this->load->model('Barang_model');
        
        $stats = [
            'total_barang' => $this->db->where('status', 'aktif')->count_all_results('barang'),
            'total_tube' => $this->Barang_model->get_total_by_kategori('Tube'),
            'total_tire' => $this->Barang_model->get_total_by_kategori('Tire'),
            'total_packing' => $this->db->count_all_results('packing_list'),
            'packing_pending' => $this->get_packing_pending(),
            'total_stok_minimum' => $this->db->where('stok <= stok_minimum')->where('status', 'aktif')->count_all_results('barang')
        ];
        
        return $stats;
    }

    public function get_recent_activities($limit = 5) {
        $this->db->select('*, DATE_FORMAT(created_at, "%H:%i") as time, CONCAT(jenis, " - ", keterangan) as content');
        $this->db->from('log_stok');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_recent_packing($limit = 5) {
        $this->db->select('p.*, 
            (SELECT COUNT(*) FROM packing_items pi WHERE pi.packing_id = p.id AND pi.kategori = "Tube") as tube_count,
            (SELECT COUNT(*) FROM packing_items pi WHERE pi.packing_id = p.id AND pi.kategori = "Tire") as tire_count'
        );
        $this->db->from('packing_list p');
        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_monthly_stats($month, $year) {
        $start_date = date('Y-m-01', strtotime("$year-$month-01"));
        $end_date = date('Y-m-t', strtotime("$year-$month-01"));
        
        $stats = [
            'packing_created' => $this->db
                ->where('DATE(created_at) >=', $start_date)
                ->where('DATE(created_at) <=', $end_date)
                ->count_all_results('packing_list'),
            'packing_this_month' => $this->db
                ->where('DATE(tanggal) >=', $start_date)
                ->where('DATE(tanggal) <=', $end_date)
                ->count_all_results('packing_list'),
            'barang_masuk' => $this->db
                ->where('jenis', 'masuk')
                ->where('DATE(tanggal) >=', $start_date)
                ->where('DATE(tanggal) <=', $end_date)
                ->count_all_results('log_stok'),
            'barang_keluar' => $this->db
                ->where('jenis', 'keluar')
                ->where('DATE(tanggal) >=', $start_date)
                ->where('DATE(tanggal) <=', $end_date)
                ->count_all_results('log_stok'),
            'keluar_this_month' => $this->db
                ->where('jenis', 'keluar')
                ->where('DATE(tanggal) >=', $start_date)
                ->where('DATE(tanggal) <=', $end_date)
                ->count_all_results('log_stok'),
            'loading_this_month' => $this->db
                ->where('status_scan_in', 'completed')
                ->where('DATE(updated_at) >=', $start_date)
                ->where('DATE(updated_at) <=', $end_date)
                ->count_all_results('packing_list')
        ];
        
        return $stats;
    }

    public function get_category_stats($kategori) {
        $this->db->select('b.*');
        $this->db->from('barang b');
        $this->db->where('b.kategori', $kategori);
        $this->db->where('b.status', 'aktif');
        $this->db->order_by('b.stok', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        
        return [
            'total' => $this->db->where('kategori', $kategori)->where('status', 'aktif')->count_all_results('barang'),
            'total_stok' => $this->db->select_sum('stok')->where('kategori', $kategori)->where('status', 'aktif')->get('barang')->row()->stok,
            'items' => $query->result_array()
        ];
    }

    public function get_stok_comparison_data() {
        // Ambil data perbandingan yang lebih lengkap
        $comparison_data = [];
        
        // Data Tube
        $tube_stats = $this->get_category_stats('Tube');
        $tube_data = [
            'kategori' => 'Tube',
            'jumlah_barang' => $tube_stats['total'],
            'total_stok' => $tube_stats['total_stok'] ?: 0,
            'items' => $tube_stats['items']
        ];
        $comparison_data[] = $tube_data;
        
        // Data Tire
        $tire_stats = $this->get_category_stats('Tire');
        $tire_data = [
            'kategori' => 'Tire',
            'jumlah_barang' => $tire_stats['total'],
            'total_stok' => $tire_stats['total_stok'] ?: 0,
            'items' => $tire_stats['items']
        ];
        $comparison_data[] = $tire_data;
        
        return $comparison_data;
    }
}
