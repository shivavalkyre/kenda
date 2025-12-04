<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ==================== MAIN ROUTES ====================
$route['dashboard'] = 'Gudang/index';
$route['stok'] = 'Gudang/stok';
$route['barang'] = 'Gudang/barang';
$route['scan'] = 'Gudang/scan';
$route['packing_list'] = 'Gudang/packing_list';
$route['kategori'] = 'Gudang/kategori';

// Cetak packing
$route['packing_list/cetak/(:num)'] = 'Gudang/cetak_packing/$1';

// ==================== BARANG ROUTES (untuk packing list) ====================
$route['packing-list/api/barang'] = 'Gudang/api_barang_for_packing';
$route['gudang/api_detail_barang/(:any)'] = 'Gudang/api_detail_barang/$1';
$route['gudang/api_list_barang'] = 'Gudang/api_list_barang';
$route['gudang/api_list_barang_paginated'] = 'Gudang/api_list_barang_paginated';
$route['gudang/api_barang_statistics'] = 'Gudang/api_barang_statistics';
$route['gudang/tambah_barang'] = 'Gudang/tambah_barang';
$route['gudang/update_barang'] = 'Gudang/update_barang';
$route['gudang/hapus_barang/(:any)'] = 'Gudang/hapus_barang/$1';
$route['gudang/stok_awal'] = 'Gudang/stok_awal';
$route['gudang/barang_masuk'] = 'Gudang/barang_masuk';
$route['gudang/barang_keluar'] = 'Gudang/barang_keluar';
$route['gudang/adjustment_stok'] = 'Gudang/adjustment_stok';
$route['gudang/export_barang'] = 'Gudang/export_barang';
$route['packing_list/cetak/(:num)'] = 'Gudang/cetak_packing/$1';

// ==================== KATEGORI ROUTES ====================
$route['kategori/api/list'] = 'Gudang/api_list_kategori';
$route['kategori/api/statistics'] = 'Gudang/api_kategori_statistics';
$route['kategori/api/detail/(:num)'] = 'Gudang/api_detail_kategori/$1';
$route['kategori/simpan'] = 'Gudang/simpan_kategori';
$route['kategori/update'] = 'Gudang/update_kategori';
$route['kategori/hapus/(:num)'] = 'Gudang/hapus_kategori/$1';

// ==================== PACKING LIST ROUTES ====================
$route['packing-list/api/list'] = 'Gudang/api_list_packing';
$route['packing-list/api/detail/(:num)'] = 'Gudang/api_detail_packing/$1';
$route['packing-list/simpan'] = 'Gudang/simpan_packing';
$route['packing-list/update'] = 'Gudang/update_packing';
$route['packing-list/delete/(:num)'] = 'Gudang/delete_packing/$1';
$route['packing-list/cetak-label/(:num)'] = 'Gudang/cetak_label/$1';
$route['packing-list/cetak-label-multiple'] = 'Gudang/cetak_label_multiple';
$route['packing-list/api/update-status'] = 'packing_list/api_update_status';
$route['packing-list/api/update-status-batch'] = 'packing_list/api_update_status_batch';

// ==================== SCAN ROUTES ====================
$route['packing-list/api/scan-out'] = 'Gudang/api_scan_out';
$route['packing-list/api/undo-scan-out'] = 'Gudang/api_undo_scan_out';
$route['packing-list/api/scan-in'] = 'Gudang/api_scan_in';
$route['packing-list/api/complete-loading'] = 'Gudang/api_complete_loading';
$route['packing-list/api/cetak-label'] = 'Gudang/api_cetak_label';
$route['packing-list/api/check-label/(:any)'] = 'Gudang/api_check_label/$1';
$route['packing-list/api/process-scan'] = 'Gudang/api_process_scan';
$route['packing-list/api/today-scan-stats'] = 'Gudang/api_today_scan_stats';


// Packing List API Routes
// $route['packing-list/api/list'] = 'api/packing_list_api/list';
// $route['packing-list/api/detail/(:num)'] = 'api/packing_list_api/detail/$1';
// $route['packing-list/api/barang'] = 'api/packing_list_api/barang';
// $route['packing-list/api/update-status'] = 'api/packing_list_api/update_status';
// $route['packing-list/api/update-status-batch'] = 'api/packing_list_api/update_status_batch';
// $route['packing-list/api/scan-out'] = 'api/packing_list_api/scan_out';
// $route['packing-list/api/undo-scan-out'] = 'api/packing_list_api/undo_scan_out';
// $route['packing-list/api/scan-in'] = 'api/packing_list_api/scan_in';
// $route['packing-list/api/complete-loading'] = 'api/packing_list_api/complete_loading';

// ==================== LABEL ROUTES ====================
$route['labels/generate'] = 'Gudang/api_generate_labels';
$route['labels/check/(:any)'] = 'Gudang/api_check_label/$1';
$route['labels/scan'] = 'Gudang/api_process_label_scan';
$route['labels/scan-batch'] = 'Gudang/api_batch_scan_labels';
$route['labels/statistics'] = 'Gudang/api_label_statistics';
$route['labels/print-batch'] = 'Gudang/api_cetak_label_batch';
$route['labels/list/(:num)'] = 'Gudang/api_get_labels_by_packing/$1';

// ==================== LABEL FORMAT ROUTES ====================
$route['packing-list/pilih-format/(:num)'] = 'Gudang/pilih_format_label/$1';
$route['packing-list/api/generate-format'] = 'Gudang/api_generate_label_format';
$route['packing-list/cetak-format/(:num)/(:any)'] = 'Gudang/cetak_label_format/$1/$2';
$route['packing-list/cetak-multiple-format'] = 'Gudang/cetak_multiple_labels_format';
$route['packing-list/view-labels/(:num)'] = 'Gudang/view_packing_labels/$1';

// ==================== DEFAULT ROUTES ====================
$route['default_controller'] = 'Gudang';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
