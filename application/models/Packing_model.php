<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packing_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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

    public function get_packing_by_ids($ids) {
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

    /**
     * Get packing by label
     */
    public function get_packing_by_label($label_code) {
        $this->db->where('no_packing', $label_code);
        return $this->db->get('packing_list')->row_array();
    }
}
?>
