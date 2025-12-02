<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get dashboard statistics
     */
    public function get_dashboard_stats() {
        $stats = [];
        
        // Total barang
        $this->db->where('status', 'aktif');
        $stats['total_barang'] = $this->db->count_all_results('barang');
        
        // Total tube
        $this->db->where('kategori', 'Tube');
        $this->db->where('status', 'aktif');
        $stats['total_tube'] = $this->db->count_all_results('barang');
        
        // Total tire
        $this->db->where('kategori', 'Tire');
        $this->db->where('status', 'aktif');
        $stats['total_tire'] = $this->db->count_all_results('barang');
        
        // Packing pending
        $this->db->where('status_scan_out', 'printed');
        $this->db->where('status_scan_in', 'pending');
        $stats['packing_pending'] = $this->db->count_all_results('packing_list');
        
        // Stok minimum
        $this->db->where('stok <= stok_minimum');
        $this->db->where('status', 'aktif');
        $stats['stok_minimum'] = $this->db->count_all_results('barang');
        
        return $stats;
    }
    
    /**
     * Get recent activities
     */
    public function get_recent_activities($limit = 5) {
        // Gabungkan berbagai aktivitas
        $activities = [];
        
        // 1. Aktivitas packing
        $this->db->select("no_packing as code, created_at as waktu, 'Packing list dibuat' as aktivitas, customer as detail", false);
        $this->db->from('packing_list');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $packing_activities = $this->db->get()->result_array();
        
        foreach ($packing_activities as $activity) {
            $activities[] = [
                'time' => date('H:i', strtotime($activity['waktu'])),
                'content' => '<strong>Packing List #' . $activity['code'] . ' dibuat</strong> - Customer: ' . $activity['detail']
            ];
        }
        
        // 2. Aktivitas scan
        $this->db->select("no_packing as code, scan_out_time as waktu, 'Label discan keluar' as aktivitas", false);
        $this->db->from('packing_list');
        $this->db->where('scan_out_time IS NOT NULL');
        $this->db->order_by('scan_out_time', 'DESC');
        $this->db->limit($limit);
        $scan_activities = $this->db->get()->result_array();
        
        foreach ($scan_activities as $activity) {
            if (!empty($activity['waktu'])) {
                $activities[] = [
                    'time' => date('H:i', strtotime($activity['waktu'])),
                    'content' => '<strong>Label #' . $activity['code'] . ' discan keluar</strong>'
                ];
            }
        }
        
        // 3. Aktivitas stok
        $this->db->select("kode_barang, created_at, jenis, qty, keterangan", false);
        $this->db->from('log_stok');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $stok_activities = $this->db->get()->result_array();
        
        foreach ($stok_activities as $activity) {
            $activities[] = [
                'time' => date('H:i', strtotime($activity['created_at'])),
                'content' => '<strong>Stok ' . $activity['jenis'] . '</strong> - ' . $activity['kode_barang'] . ': ' . $activity['qty'] . ' unit'
            ];
        }
        
        // Sort activities by time (descending)
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        // Return limited activities
        return array_slice($activities, 0, $limit);
    }
    
    /**
     * Get recent packing lists
     */
    public function get_recent_packing($limit = 5) {
        $this->db->select('p.*, COUNT(pi.id) as item_count, 
                          SUM(CASE WHEN pi.kategori = "Tube" THEN pi.qty ELSE 0 END) as tube_count,
                          SUM(CASE WHEN pi.kategori = "Tire" THEN pi.qty ELSE 0 END) as tire_count');
        $this->db->from('packing_list p');
        $this->db->join('packing_items pi', 'p.id = pi.packing_id', 'left');
        $this->db->group_by('p.id');
        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit($limit);
        
        $result = $this->db->get()->result_array();
        
        // Format hasil
        foreach ($result as &$packing) {
            $packing['label_status'] = $this->get_label_status_text($packing['status_scan_out']);
            $packing['loading_status'] = $this->get_loading_status_text($packing['status_scan_in']);
        }
        
        return $result;
    }
    
    /**
     * Get monthly statistics
     */
    public function get_monthly_stats($month = null, $year = null) {
        if (!$month) $month = date('m');
        if (!$year) $year = date('Y');
        
        $stats = [];
        
        // Packing bulan ini
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        $stats['packing_this_month'] = $this->db->count_all_results('packing_list');
        
        // Packing completed bulan ini
        $this->db->where('MONTH(scan_in_time)', $month);
        $this->db->where('YEAR(scan_in_time)', $year);
        $this->db->where('status_scan_in', 'completed');
        $stats['loading_this_month'] = $this->db->count_all_results('packing_list');
        
        // Barang keluar bulan ini
        $this->db->select('SUM(qty) as total');
        $this->db->where('jenis', 'keluar');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        $query = $this->db->get('log_stok')->row();
        $stats['keluar_this_month'] = $query->total ?: 0;
        
        return $stats;
    }
    
    /**
     * Get category statistics
     */
    public function get_category_stats($category) {
        $this->db->select('nama_barang, stok, stok_minimum');
        $this->db->from('barang');
        $this->db->where('kategori', $category);
        $this->db->where('status', 'aktif');
        $this->db->order_by('stok', 'DESC');
        $this->db->limit(5);
        
        $items = $this->db->get()->result_array();
        
        $total_stok = array_sum(array_column($items, 'stok'));
        $total_minimum = array_sum(array_column($items, 'stok_minimum'));
        
        return [
            'items' => $items,
            'total_stok' => $total_stok,
            'total_minimum' => $total_minimum
        ];
    }
    
    /**
     * Get stok comparison data
     */
    public function get_stok_comparison_data() {
        $data = [];
        
        // Stok saat ini
        $this->db->select_sum('stok', 'total');
        $this->db->where('status', 'aktif');
        $query = $this->db->get('barang')->row();
        $data['stok_saat_ini'] = $query->total ?: 0;
        
        // Stok minimum total
        $this->db->select_sum('stok_minimum', 'total');
        $this->db->where('status', 'aktif');
        $query = $this->db->get('barang')->row();
        $data['stok_minimum_total'] = $query->total ?: 0;
        
        // Packing bulan ini (item count)
        $month = date('m');
        $year = date('Y');
        
        $this->db->select('SUM(jumlah_item) as total');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        $query = $this->db->get('packing_list')->row();
        $data['packing_bulan_ini'] = $query->total ?: 0;
        
        // Keluar bulan ini
        $this->db->select('SUM(qty) as total');
        $this->db->where('jenis', 'keluar');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        $query = $this->db->get('log_stok')->row();
        $data['keluar_bulan_ini'] = $query->total ?: 0;
        
        // Loading bulan ini
        $this->db->select('SUM(jumlah_item) as total');
        $this->db->where('MONTH(scan_in_time)', $month);
        $this->db->where('YEAR(scan_in_time)', $year);
        $this->db->where('status_scan_in', 'completed');
        $query = $this->db->get('packing_list')->row();
        $data['loading_bulan_ini'] = $query->total ?: 0;
        
        return $data;
    }
    
    /**
     * Helper function for label status
     */
    private function get_label_status_text($status) {
        switch($status) {
            case 'draft': return 'Belum';
            case 'printed': return 'Tercetak';
            case 'scanned_out': return 'Discan';
            default: return 'Belum';
        }
    }
    
    /**
     * Helper function for loading status
     */
    private function get_loading_status_text($status) {
        switch($status) {
            case 'pending': return 'Pending';
            case 'scanned_in': return 'Discan';
            case 'completed': return 'Selesai';
            default: return 'Pending';
        }
    }
}
