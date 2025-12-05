<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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
}
?>
