<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Gudang Routes
$route['dashboard'] = 'Gudang/index';
$route['stok'] = 'Gudang/stok';
$route['barang'] = 'Gudang/barang';
$route['scan'] = 'Gudang/scan';
$route['packing_list'] = 'Gudang/packing_list';
$route['kategori'] = 'Gudang/kategori';

// API Routes for Kategori
$route['kategori/api/list'] = 'Gudang/api_list_kategori';
$route['kategori/api/statistics'] = 'Gudang/api_kategori_statistics';
$route['kategori/api/detail/(:num)'] = 'Gudang/api_detail_kategori/$1';
$route['kategori/simpan'] = 'Gudang/simpan_kategori';
$route['kategori/update'] = 'Gudang/update_kategori';
$route['kategori/hapus/(:num)'] = 'Gudang/hapus_kategori/$1';

// API Routes for Packing List
$route['packing-list/api/list'] = 'Gudang/api_list_packing';
$route['packing-list/api/detail/(:num)'] = 'Gudang/api_detail_packing/$1';
$route['packing-list/api/barang'] = 'Gudang/api_list_barang';
$route['packing-list/simpan'] = 'Gudang/simpan_packing';
$route['packing-list/update'] = 'Gudang/update_packing';
$route['packing-list/delete/(:num)'] = 'Gudang/delete_packing/$1';

// API Routes for Scan Actions
$route['packing-list/api/scan-out'] = 'Gudang/api_scan_out';
$route['packing-list/api/undo-scan-out'] = 'Gudang/api_undo_scan_out';
$route['packing-list/api/scan-in'] = 'Gudang/api_scan_in';
$route['packing-list/api/complete-loading'] = 'Gudang/api_complete_loading';
$route['packing-list/api/cetak-label'] = 'Gudang/api_cetak_label';

// API Routes for Scan Module
$route['packing-list/api/check-label/(:any)'] = 'Gudang/api_check_label/$1';
$route['packing-list/api/process-scan'] = 'Gudang/api_process_scan';
$route['packing-list/api/today-scan-stats'] = 'Gudang/api_today_scan_stats';

$route['gudang/api_detail_barang/(:any)'] = 'Gudang/api_detail_barang/$1';
$route['gudang/tambah_barang'] = 'Gudang/tambah_barang';
$route['gudang/update_barang'] = 'Gudang/update_barang';
$route['gudang/hapus_barang/(:any)'] = 'Gudang/hapus_barang/$1';
$route['gudang/stok_awal'] = 'Gudang/stok_awal';
$route['gudang/barang_masuk'] = 'Gudang/barang_masuk';
$route['gudang/export_barang'] = 'Gudang/export_barang';





// Default Route
$route['default_controller'] = 'Gudang';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
