<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Label_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ==================== LABEL METHODS ====================

    /**
     * Generate label code
     */
    public function generate_label_code($prefix = 'LBL', $length = 6) {
        do {
            $random = strtoupper(bin2hex(random_bytes(ceil($length/2))));
            $label_code = $prefix . substr($random, 0, $length);
            
            // Check if exists
            $this->db->where('label_code', $label_code);
            $exists = $this->db->count_all_results('labels') > 0;
        } while ($exists);
        
        return $label_code;
    }

    /**
     * Create labels for packing list
     */
    public function create_labels_for_packing($packing_id, $label_count = 1, $label_type = 'single') {
        $this->db->trans_start();
        
        // Check if packing exists
        $this->db->where('id', $packing_id);
        $packing = $this->db->get('packing_list')->row_array();
        
        if (!$packing) {
            return false;
        }
        
        $labels = [];
        
        // Create master label for multi-label system
        if ($label_count > 1) {
            $master_label = [
                'label_code' => $this->generate_label_code('MLBL'),
                'packing_id' => $packing_id,
                'label_type' => 'master',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('labels', $master_label);
            $master_label_id = $this->db->insert_id();
            
            // Create child labels
            for ($i = 1; $i <= $label_count; $i++) {
                $child_label = [
                    'label_code' => $this->generate_label_code('CLBL'),
                    'packing_id' => $packing_id,
                    'label_type' => 'child',
                    'parent_label_id' => $master_label_id,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->insert('labels', $child_label);
                $labels[] = array_merge($child_label, ['id' => $this->db->insert_id()]);
            }
            
            // Update master label status
            $this->db->where('id', $master_label_id);
            $this->db->update('labels', ['total_children' => $label_count]);
            
        } else {
            // Single label system
            $label_data = [
                'label_code' => $this->generate_label_code('LBL'),
                'packing_id' => $packing_id,
                'label_type' => 'single',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('labels', $label_data);
            $label_id = $this->db->insert_id();
            $labels[] = array_merge($label_data, ['id' => $label_id]);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $labels : false;
    }

    /**
     * Get label by code
     */
    public function get_label_by_code($label_code) {
        $this->db->select('l.*, p.*, 
            COUNT(DISTINCT lc.id) as total_children,
            SUM(CASE WHEN lc.status = "scanned_out" THEN 1 ELSE 0 END) as scanned_out_children,
            SUM(CASE WHEN lc.status = "scanned_in" THEN 1 ELSE 0 END) as scanned_in_children,
            SUM(CASE WHEN lc.status = "completed" THEN 1 ELSE 0 END) as completed_children');
        $this->db->from('labels l');
        $this->db->join('packing_list p', 'l.packing_id = p.id', 'left');
        $this->db->join('labels lc', 'lc.parent_label_id = l.id AND l.label_type = "master"', 'left');
        $this->db->where('l.label_code', $label_code);
        $this->db->group_by('l.id');
        
        return $this->db->get()->row_array();
    }

    /**
     * Get all labels for packing list
     */
    public function get_labels_by_packing($packing_id) {
        $this->db->select('l.*');
        $this->db->from('labels l');
        $this->db->where('l.packing_id', $packing_id);
        $this->db->order_by('l.label_type', 'DESC');
        $this->db->order_by('l.id', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Process label scan
     */
    public function process_label_scan($label_id, $action, $user_id = null) {
        $valid_actions = ['printed', 'scanned_out', 'scanned_in', 'completed', 'void'];
        
        if (!in_array($action, $valid_actions)) {
            return false;
        }
        
        $time_field_map = [
            'printed' => 'printed_at',
            'scanned_out' => 'scanned_out_at',
            'scanned_in' => 'scanned_in_at',
            'completed' => 'completed_at'
        ];
        
        $this->db->trans_start();
        
        // Update label status
        $update_data = [
            'status' => $action,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if (isset($time_field_map[$action])) {
            $update_data[$time_field_map[$action]] = date('Y-m-d H:i:s');
        }
        
        $this->db->where('id', $label_id);
        $this->db->update('labels', $update_data);
        
        // Log the scan
        $log_data = [
            'label_id' => $label_id,
            'action' => $action,
            'user_id' => $user_id,
            'scan_time' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('label_scan_logs', $log_data);
        
        // If this is a child label, check parent status
        $this->db->select('parent_label_id, status');
        $this->db->where('id', $label_id);
        $label = $this->db->get('labels')->row_array();
        
        if ($label && $label['parent_label_id']) {
            $this->_update_parent_label_status($label['parent_label_id']);
        }
        
        // Update packing list status if all labels are scanned
        $this->db->select('packing_id');
        $this->db->where('id', $label_id);
        $packing_id_result = $this->db->get('labels')->row();
        
        if ($packing_id_result) {
            $this->_update_packing_status($packing_id_result->packing_id);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    /**
     * Update parent label status based on children
     */
    private function _update_parent_label_status($parent_label_id) {
        $this->db->select('
            COUNT(*) as total_children,
            SUM(CASE WHEN status = "scanned_out" THEN 1 ELSE 0 END) as scanned_out,
            SUM(CASE WHEN status = "scanned_in" THEN 1 ELSE 0 END) as scanned_in,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'
        );
        $this->db->where('parent_label_id', $parent_label_id);
        $stats = $this->db->get('labels')->row_array();
        
        $new_status = 'active';
        
        if ($stats['completed'] == $stats['total_children']) {
            $new_status = 'completed';
        } elseif ($stats['scanned_in'] == $stats['total_children']) {
            $new_status = 'scanned_in';
        } elseif ($stats['scanned_out'] == $stats['total_children']) {
            $new_status = 'scanned_out';
        }
        
        $this->db->where('id', $parent_label_id);
        $this->db->update('labels', ['status' => $new_status]);
    }

    /**
     * Update packing list status based on labels
     */
    private function _update_packing_status($packing_id) {
        $this->db->select('
            COUNT(*) as total_labels,
            SUM(CASE WHEN status = "scanned_out" THEN 1 ELSE 0 END) as scanned_out,
            SUM(CASE WHEN status = "scanned_in" THEN 1 ELSE 0 END) as scanned_in,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'
        );
        $this->db->where('packing_id', $packing_id);
        $stats = $this->db->get('labels')->row_array();
        
        $update_data = [];
        
        if ($stats['completed'] > 0) {
            $update_data['status_scan_in'] = 'completed';
        } elseif ($stats['scanned_in'] > 0) {
            $update_data['status_scan_in'] = 'scanned_in';
        } elseif ($stats['scanned_out'] > 0) {
            $update_data['status_scan_out'] = 'scanned_out';
        }
        
        if (!empty($update_data)) {
            $this->db->where('id', $packing_id);
            $this->db->update('packing_list', $update_data);
        }
    }

    /**
     * Get label statistics
     */
    public function get_label_statistics($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $stats = [
            'total_labels' => $this->db->where('DATE(created_at)', $date)->count_all_results('labels'),
            'printed_today' => $this->db->where('DATE(printed_at)', $date)->count_all_results('labels'),
            'scanned_out_today' => $this->db->where('DATE(scanned_out_at)', $date)->count_all_results('labels'),
            'scanned_in_today' => $this->db->where('DATE(scanned_in_at)', $date)->count_all_results('labels'),
            'completed_today' => $this->db->where('DATE(completed_at)', $date)->count_all_results('labels'),
        ];
        
        return $stats;
    }

    // ==================== LABEL GENERATION METHODS ====================

    /**
     * Generate labels for packing list dengan format sesuai gambar
     */
    public function generate_labels_with_format($packing_id, $label_count = 1, $label_format = 'kenda', $label_type = 'single') {
        $this->db->trans_start();
        
        try {
            // Get packing data
            $this->db->where('id', $packing_id);
            $packing = $this->db->get('packing_list')->row_array();
            
            if (!$packing) {
                throw new Exception('Packing tidak ditemukan');
            }
            
            $labels = [];
            $current_date = date('Ymd');
            $timestamp = time();
            
            // Jika multiple labels
            if ($label_type === 'multiple' && $label_count > 1) {
                // Generate master label
                $master_code = 'ML' . $current_date . str_pad($packing_id, 4, '0', STR_PAD_LEFT) . '000';
                
                // Pastikan kode master unik
                $this->db->where('label_code', $master_code);
                $master_exists = $this->db->get('labels')->row();
                
                if ($master_exists) {
                    // Tambah angka random jika sudah ada
                    $master_code .= rand(100, 999);
                }
                
                $master_label = [
                    'label_code' => $master_code,
                    'packing_id' => $packing_id,
                    'label_type' => 'master',
                    'label_format' => $label_format,
                    'status' => 'active',
                    'total_bales' => $label_count,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->insert('labels', $master_label);
                $master_id = $this->db->insert_id();
                
                // Generate child labels
                for ($i = 1; $i <= $label_count; $i++) {
                    $child_code = 'CL' . $current_date . str_pad($packing_id, 4, '0', STR_PAD_LEFT) . str_pad($i, 3, '0', STR_PAD_LEFT);
                    
                    // Pastikan kode child unik
                    $this->db->where('label_code', $child_code);
                    $child_exists = $this->db->get('labels')->row();
                    
                    if ($child_exists) {
                        $child_code .= rand(100, 999);
                    }
                    
                    $child_label = [
                        'label_code' => $child_code,
                        'packing_id' => $packing_id,
                        'label_type' => 'child',
                        'parent_label_id' => $master_id,
                        'label_format' => $label_format,
                        'bale_number' => $i,
                        'total_bales' => $label_count,
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->db->insert('labels', $child_label);
                    $child_id = $this->db->insert_id();
                    
                    $labels[] = [
                        'id' => $child_id,
                        'label_code' => $child_code,
                        'label_type' => 'child',
                        'bale_number' => $i,
                        'total_bales' => $label_count
                    ];
                }
                
            } else {
                // Single label
                $label_code = 'LBL' . $current_date . str_pad($packing_id, 4, '0', STR_PAD_LEFT) . '001';
                
                // Pastikan kode unik
                $this->db->where('label_code', $label_code);
                $label_exists = $this->db->get('labels')->row();
                
                if ($label_exists) {
                    $label_code .= rand(100, 999);
                }
                
                $label_data = [
                    'label_code' => $label_code,
                    'packing_id' => $packing_id,
                    'label_type' => 'single',
                    'label_format' => $label_format,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->insert('labels', $label_data);
                $label_id = $this->db->insert_id();
                
                $labels[] = [
                    'id' => $label_id,
                    'label_code' => $label_code,
                    'label_type' => 'single'
                ];
            }
            
            // Update packing status
            $this->db->where('id', $packing_id);
            $this->db->update('packing_list', [
                'status_scan_out' => 'printed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Database transaction failed');
            }
            
            return $labels;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Generate labels error: ' . $e->getMessage());
            return false;
        }
    }

    /**
    * Get label data by format
    */
    public function get_label_data_for_print($label_id, $format = 'kenda') {
        $this->db->select('l.*, p.*');
        $this->db->from('labels l');
        $this->db->join('packing_list p', 'l.packing_id = p.id', 'left');
        $this->db->where('l.id', $label_id);
        
        $label = $this->db->get()->row_array();
        
        if (!$label) {
            return false;
        }
        
        // Get items untuk label
        $this->db->select('pi.*, b.*');
        $this->db->from('packing_items pi');
        $this->db->join('barang b', 'pi.kode_barang = b.kode_barang', 'left');
        $this->db->where('pi.packing_id', $label['packing_id']);
        $items = $this->db->get()->result_array();
        
        // Format data berdasarkan jenis label
        $label_data = [
            'label' => $label,
            'packing' => $label,
            'items' => $items,
            'format' => $format
        ];
        
        // Tambahkan data spesifik berdasarkan format
        switch ($format) {
            case 'kenda':
                $label_data = array_merge($label_data, $this->_format_kenda_label($label, $items));
                break;
            case 'xds':
                $label_data = array_merge($label_data, $this->_format_xds_label($label, $items));
                break;
            case 'btg':
                $label_data = array_merge($label_data, $this->_format_btg_label($label, $items));
                break;
            case 'standard':
                $label_data = array_merge($label_data, $this->_format_standard_label($label, $items));
                break;
            default:
                $label_data = array_merge($label_data, $this->_format_default_label($label, $items));
        }
        
        return $label_data;
    }

    /**
    * Format untuk label KENDA (sesuai gambar 1)
    */
    private function _format_kenda_label($packing, $items) {
        $first_item = $items[0] ?? [];
        
        return [
            'company_name' => 'PT. KENDA RUBBER INDONESIA',
            'po_no' => $first_item['no_po'] ?? '251000528',
            'order_qty' => $packing['jumlah_item'] ?? 0,
            'bales_no' => isset($packing['bale_number']) ? "{$packing['bale_number']}/{$packing['total_bales']}" : '001/100',
            'qty_per_bale' => $first_item['qty'] ?? 25,
            'nw' => $first_item['net_weight'] ?? '15.200',
            'gw' => $first_item['gross_weight'] ?? '15.600',
            'item_code' => $first_item['kode_barang'] ?? 'TRERBBKBKK0120175K924',
            'cfr' => $first_item['cfr'] ?? '1.8CFT',
            'description' => $first_item['nama_barang'] ?? 'TIRE 20" X 1.75 BLACK – KENDA K924',
            'made_in' => 'MADE IN INDONESIA'
        ];
    }

    /**
    * Format untuk label XDS (sesuai gambar 2)
    */
    private function _format_xds_label($packing, $items) {
        $first_item = $items[0] ?? [];
        
        return [
            'company_name' => 'XDS BICYCLE CAMBODIA',
            'product_name' => 'BICYCLE TIRE',
            'po_no' => $first_item['no_po'] ?? '0-88130-7-1-5',
            'size' => $first_item['size'] ?? '650B*50 GRO COMP',
            'kenda_size' => $first_item['kenda_size'] ?? '650B*50 TR040 BK/BSK 60TPI R6275*2',
            'item_no' => $first_item['item_no'] ?? '1110-18373',
            'pkg_no' => $packing['no_packing'] ?? '',
            'qty' => $first_item['qty'] ?? 25,
            'nw' => $first_item['net_weight'] ?? '19.88',
            'gw' => $first_item['gross_weight'] ?? '26.25',
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

    /**
    * Get labels by packing ID
    */
    public function get_labels_by_packing_id($packing_id) {
        $this->db->where('packing_id', $packing_id);
        $this->db->order_by('label_type', 'DESC');
        $this->db->order_by('bale_number', 'ASC');
        return $this->db->get('labels')->result_array();
    }

    /**
    * Update label status to printed
    */
    public function update_label_printed($label_id) {
        $this->db->where('id', $label_id);
        return $this->db->update('labels', [
            'status' => 'printed',
            'printed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
?>
