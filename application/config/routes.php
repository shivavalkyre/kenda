<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

// Default controller
$route['default_controller'] = 'Dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ==================== DASHBOARD ROUTES ====================
$route['dashboard'] = 'Dashboard/index';
$route['dashboard/api/stats'] = 'Dashboard/api_dashboard_stats';
$route['dashboard/api/activities/(:num)'] = 'Dashboard/api_recent_activities/$1';
$route['dashboard/api/monthly-stats/(:num)/(:num)'] = 'Dashboard/api_monthly_stats/$1/$2';

// ==================== BARANG ROUTES ====================
$route['barang'] = 'Barang/index';
$route['barang/api/detail/(:any)'] = 'Barang/api_detail_barang/$1';
$route['barang/api/list'] = 'Barang/api_list_barang';
$route['barang/api/paginated'] = 'Barang/api_list_barang_paginated';
$route['barang/api/tambah'] = 'Barang/tambah_barang';
$route['barang/api/update'] = 'Barang/update_barang';
$route['barang/api/hapus/(:any)'] = 'Barang/hapus_barang/$1';

$route['barang/api/stok-awal'] = 'Barang/stok_awal';
$route['barang/api/masuk'] = 'Barang/barang_masuk';
$route['barang/api/keluar'] = 'Barang/barang_keluar';
$route['barang/api/adjustment'] = 'Barang/adjustment_stok';
$route['barang/api/statistics'] = 'Barang/api_barang_statistics';
$route['barang/api/for-packing'] = 'Barang/api_barang_for_packing';
$route['barang/export'] = 'Barang/export_barang';

// ==================== PACKING LIST ROUTES ====================
$route['packing'] = 'Packing_list/index';
$route['packing/api/list'] = 'Packing_list/api_list_packing';
$route['packing/api/detail/(:num)'] = 'Packing_list/api_detail_packing/$1';
$route['packing/api/simpan'] = 'Packing_list/simpan_packing';
$route['packing/api/update'] = 'Packing_list/update_packing';
$route['packing/api/delete/(:num)'] = 'Packing_list/delete_packing/$1';
$route['packing/api/update-status'] = 'Packing_list/api_update_status';
$route['packing/cetak/(:num)'] = 'Packing_list/cetak_packing/$1';
$route['packing/cetak-label/(:num)'] = 'Packing_list/cetak_label/$1';
$route['packing/cetak-label-multiple'] = 'Packing_list/cetak_label_multiple';
$route['packing/cetak-single-label/(:num)/(:any)'] = 'Packing_list/cetak_single_label/$1/$2';
$route['packing/cetak-multiple-labels'] = 'Packing_list/cetak_multiple_labels';

// ==================== KATEGORI ROUTES ====================
$route['kategori'] = 'Kategori/index';
$route['kategori/api/list'] = 'Kategori/api_list_kategori';
$route['kategori/api/statistics'] = 'Kategori/api_kategori_statistics';
$route['kategori/api/detail/(:num)'] = 'Kategori/api_detail_kategori/$1';
$route['kategori/api/simpan'] = 'Kategori/simpan_kategori';
$route['kategori/api/update'] = 'Kategori/update_kategori';
$route['kategori/api/hapus/(:num)'] = 'Kategori/hapus_kategori/$1';

// ==================== SCAN ROUTES ====================
$route['scan'] = 'Scan/index';
$route['scan/api/check-label/(:any)'] = 'Scan/api_check_label/$1';
$route['scan/api/process'] = 'Scan/api_process_scan';
$route['scan/api/today-stats'] = 'Scan/api_today_scan_stats';
$route['scan/api/recent-scans/(:num)'] = 'Scan/api_recent_scans/$1';
$route['scan/api/scan-out'] = 'Scan/api_scan_out';
$route['scan/api/undo-scan-out'] = 'Scan/api_undo_scan_out';
$route['scan/api/scan-in'] = 'Scan/api_scan_in';
$route['scan/api/complete'] = 'Scan/api_complete_loading';

// ==================== LABEL ROUTES ====================
$route['label'] = 'Label/index';
$route['label/api/generate-format'] = 'Label/api_generate_label_format';
$route['label/api/check/(:any)'] = 'Label/api_check_label_detail/$1';
$route['label/api/process-scan'] = 'Label/api_process_label_scan';
$route['label/api/batch-scan'] = 'Label/api_batch_scan_labels';
$route['label/api/statistics'] = 'Label/api_label_statistics';
$route['label/api/cetak-batch'] = 'Label/api_cetak_label_batch';
$route['label/pilih-format/(:num)'] = 'Label/pilih_format_label/$1';
$route['label/cetak-format/(:num)/(:any)'] = 'Label/cetak_label_format/$1/$2';
$route['label/view-labels/(:num)'] = 'Label/view_packing_labels/$1';

// ==================== GUDANG/LAPORAN ROUTES ====================
$route['gudang/stok'] = 'Gudang/stok';
$route['gudang/api/laporan-stok/(:any)'] = 'Gudang/api_laporan_stok/$1';
$route['gudang/api/statistik-stok'] = 'Gudang/api_statistik_stok';
$route['gudang/export-stok/(:any)'] = 'Gudang/export_laporan_stok/$1';

// ==================== AUTH ROUTES ====================
$route['auth/login'] = 'Auth/login';
$route['auth/process-login'] = 'Auth/process_login';
$route['auth/logout'] = 'Auth/logout';

// ==================== BACKWARD COMPATIBILITY ROUTES ====================
// Untuk memastikan URL lama masih berfungsi
$route['gudang'] = 'Dashboard/index';
$route['gudang/barang'] = 'Barang/index';
$route['gudang/packing_list'] = 'Packing_list/index';
$route['gudang/kategori'] = 'Kategori/index';
$route['gudang/scan'] = 'Scan/index';

// API routes untuk backward compatibility
$route['gudang/api_detail_barang/(:any)'] = 'Barang/api_detail_barang/$1';
$route['gudang/tambah_barang'] = 'Barang/tambah_barang';
$route['gudang/update_barang'] = 'Barang/update_barang';
$route['gudang/hapus_barang/(:any)'] = 'Barang/hapus_barang/$1';
$route['gudang/hapus_permanen_barang/(:any)'] = 'Barang/hapus_permanen_barang/$1';
$route['gudang/stok_awal'] = 'Barang/stok_awal';
$route['gudang/barang_masuk'] = 'Barang/barang_masuk';
$route['gudang/barang_keluar'] = 'Barang/barang_keluar';
$route['gudang/adjustment_stok'] = 'Barang/adjustment_stok';
$route['gudang/export_barang'] = 'Barang/export_barang';
?>
