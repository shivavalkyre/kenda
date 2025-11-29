<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['auth/login'] = 'auth/login';
$route['auth/logout'] = 'auth/logout';

// Dashboard
$route['dashboard'] = 'dashboard/index';

// Laporan Routes
$route['stok'] = 'stok/index';
$route['laporan/api/stok'] = 'stok/get_data_stok';
$route['laporan/api/chart'] = 'stok/get_chart_data';
$route['laporan/api/summary'] = 'stok/get_summary';
$route['laporan/export-stok'] = 'stok/export_stok';

// Scan Routes
$route['scan'] = 'scan/index';
$route['scan/process'] = 'scan/process_scan';
$route['scan/confirm'] = 'scan/confirm_scan';
$route['scan/stats'] = 'scan/get_today_stats';
$route['scan/recent'] = 'scan/get_recent_scans';
$route['scan/label-info/(:any)'] = 'scan/get_label_info/$1';


// Packing List Routes
$route['packing-list'] = 'packing_list/index';
$route['packing-list/simpan'] = 'packing_list/simpan';
$route['packing-list/api/list'] = 'packing_list/get_packing_list';
$route['packing-list/api/detail/(:num)'] = 'packing_list/detail/$1';
$route['packing-list/api/cetak-label'] = 'packing_list/cetak_label';
$route['packing-list/api/update-status'] = 'packing_list/update_status';
$route['packing-list/api/barang'] = 'packing_list/get_barang_list';
$route['packing-list/api/statistics'] = 'packing_list/get_statistics';
$route['packing-list/hapus/(:num)'] = 'packing_list/hapus/$1';
$route['packing-list/export'] = 'packing_list/export';

// Kategori Routes
$route['kategori'] = 'kategori/index';
$route['kategori/simpan'] = 'kategori/simpan';
$route['kategori/update'] = 'kategori/update';
$route['kategori/hapus/(:num)'] = 'kategori/hapus/$1';
$route['kategori/api/list'] = 'kategori/get_kategori';
$route['kategori/api/detail/(:num)'] = 'kategori/detail/$1';
$route['kategori/api/statistics'] = 'kategori/get_statistics';