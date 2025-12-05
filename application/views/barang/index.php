<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-boxes me-2"></i>Data Barang
                </h1>
                <p class="text-muted">Manajemen data barang dalam sistem gudang</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-primary"><?php echo $total_barang; ?></div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up me-1"></i> Stok tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-info"><?php echo $total_tube; ?></div>
                <div class="stat-label">Total Tube</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check me-1"></i> Tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-success"><?php echo $total_tire; ?></div>
                <div class="stat-label">Total Tire</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check me-1"></i> Tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-warning"><?php echo $stok_minimum; ?></div>
                <div class="stat-label">Stok Minimum</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-exclamation-triangle me-1"></i> Perlu restock
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-between">
                <div class="d-flex flex-wrap gap-2">
                    <!-- Tombol toggle untuk menampilkan nonaktif -->
                    <?php if(!$show_nonaktif): ?>
                        <a href="<?php echo site_url('gudang/barang?show_nonaktif=true'); ?>" class="btn btn-outline-warning">
                            <i class="fas fa-eye-slash me-2"></i>Tampilkan Nonaktif
                        </a>
                    <?php else: ?>
                        <a href="<?php echo site_url('gudang/barang'); ?>" class="btn btn-outline-success">
                            <i class="fas fa-eye me-2"></i>Sembunyikan Nonaktif
                        </a>
                    <?php endif; ?>
                    
                    <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
                        <i class="fas fa-plus me-2"></i>Tambah Barang
                    </button>
                    <button class="btn btn-kenda-red" id="exportBtn">
                        <i class="fas fa-file-export me-2"></i>Export Data
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-filter="all">Semua Barang</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="tube">Tube</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="tire">Tire</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="stok-minimum">Stok Minimum</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-filter="aktif">Hanya Aktif</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="nonaktif">Hanya Nonaktif</a></li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Cari barang..." id="searchInput" value="<?php echo isset($search) ? htmlspecialchars($search, ENT_QUOTES, 'UTF-8') : ''; ?>">
                        <button class="btn btn-kenda" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Barang
                        <?php if($show_nonaktif): ?>
                            <span class="badge bg-warning ms-2">
                                <i class="fas fa-eye-slash me-1"></i>Menampilkan Nonaktif
                            </span>
                        <?php endif; ?>
                        <?php if(isset($search) && !empty($search)): ?>
                            <span class="badge bg-info ms-2">Search: "<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"</span>
                        <?php endif; ?>
                        <?php if(isset($filter) && $filter != 'all'): ?>
                            <span class="badge bg-secondary ms-2">Filter: <?php echo ucfirst(str_replace('-', ' ', $filter)); ?></span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="barangTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th width="60">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th>Stok Minimum</th>
                                    <th>Status</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($barang_list)): ?>
                                    <?php 
                                    $current_page = isset($current_page) ? $current_page : 1;
                                    $limit = isset($limit) ? $limit : 10;
                                    $no = 1 + (($current_page - 1) * $limit);
                                    foreach($barang_list as $barang): 
                                    ?>
                                        <tr data-kategori="<?php echo htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8'); ?>" 
                                            data-stok="<?php echo $barang['stok']; ?>" 
                                            data-stok-min="<?php echo $barang['stok_minimum']; ?>"
                                            data-status="<?php echo htmlspecialchars($barang['status'], ENT_QUOTES, 'UTF-8'); ?>"
                                            class="<?php echo $barang['status'] == 'nonaktif' ? 'table-warning' : ''; ?>">
                                            <td>
                                                <input type="checkbox" class="row-checkbox" value="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>">
                                            </td>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <span class="badge bg-dark"><?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40 me-3">
                                                        <div class="symbol-label bg-light-<?php echo $barang['kategori'] == 'Tube' ? 'primary' : ($barang['kategori'] == 'Tire' ? 'success' : 'info'); ?>">
                                                            <i class="fas fa-<?php echo $barang['kategori'] == 'Tube' ? 'cog' : ($barang['kategori'] == 'Tire' ? 'tire' : 'box'); ?> text-<?php echo $barang['kategori'] == 'Tube' ? 'primary' : ($barang['kategori'] == 'Tire' ? 'success' : 'info'); ?>"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        <div class="text-muted small"><?php echo $barang['deskripsi'] ? htmlspecialchars(substr($barang['deskripsi'], 0, 50) . '...', ENT_QUOTES, 'UTF-8') : '-'; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $barang['kategori'] == 'Tube' ? 'primary' : ($barang['kategori'] == 'Tire' ? 'success' : 'info'); ?>">
                                                    <?php echo htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold <?php echo $barang['stok'] <= $barang['stok_minimum'] ? 'text-danger' : 'text-success'; ?>">
                                                        <?php echo $barang['stok']; ?>
                                                    </span>
                                                    <?php if($barang['stok'] <= $barang['stok_minimum']): ?>
                                                        <span class="badge bg-warning ms-2" data-bs-toggle="tooltip" title="Stok minimum: <?php echo $barang['stok_minimum']; ?>">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <span class="text-muted"><?php echo $barang['stok_minimum']; ?></span>
                                            </td>
                                            <td>
                                                <?php if($barang['status'] == 'aktif'): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary detail-btn" 
                                                            data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-bs-toggle="tooltip" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning edit-btn" 
                                                            data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if($barang['status'] == 'nonaktif'): ?>
                                                        <!-- Tombol aktifkan untuk barang nonaktif -->
                                                        <button type="button" class="btn btn-sm btn-outline-success aktifkan-btn" 
                                                                data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-bs-toggle="tooltip" title="Aktifkan Barang">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <?php if($barang['status'] == 'aktif'): ?>
                                                                <li>
                                                                    <a class="dropdown-item stok-awal-btn" href="#"
                                                                       data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-nama="<?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-kategori="<?php echo htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-satuan="<?php echo htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                        <i class="fas fa-database me-2"></i>Input Stok Awal
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item barang-masuk-btn" href="#"
                                                                       data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-nama="<?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-kategori="<?php echo htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-satuan="<?php echo htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-stok="<?php echo $barang['stok']; ?>">
                                                                        <i class="fas fa-arrow-down me-2"></i>Barang Masuk
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item barang-keluar-btn" href="#"
                                                                       data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-nama="<?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-kategori="<?php echo htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-satuan="<?php echo htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-stok="<?php echo $barang['stok']; ?>">
                                                                        <i class="fas fa-arrow-up me-2"></i>Barang Keluar
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item adjustment-btn" href="#"
                                                                       data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-nama="<?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-kategori="<?php echo htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-satuan="<?php echo htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                       data-stok="<?php echo $barang['stok']; ?>">
                                                                        <i class="fas fa-exchange-alt me-2"></i>Adjustment
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                            <?php endif; ?>
                                                            <li>
                                                                <a class="dropdown-item text-danger hapus-btn" href="#"
                                                                   data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                   data-nama="<?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                    <i class="fas fa-trash me-2"></i><?php echo $barang['status'] == 'aktif' ? 'Hapus' : 'Hapus Permanen'; ?>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <p>Tidak ada data barang</p>
                                                <?php if(isset($search) && !empty($search)): ?>
                                                    <p class="small">Coba dengan kata kunci lain atau <a href="<?php echo site_url('gudang/barang'); ?>" class="text-decoration-none">reset pencarian</a></p>
                                                <?php else: ?>
                                                    <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
                                                        <i class="fas fa-plus me-2"></i>Tambah Barang Pertama
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="text-muted">
                                <?php if(isset($search) && !empty($search) || isset($filter) && $filter != 'all'): ?>
                                    Menampilkan <strong><?php echo count($barang_list); ?></strong> dari <strong><?php echo isset($total_filtered) ? $total_filtered : $total_barang; ?></strong> barang
                                <?php else: ?>
                                    Total <strong><?php echo $total_barang; ?></strong> barang
                                <?php endif; ?>
                                <?php if($show_nonaktif): ?>
                                    <span class="badge bg-warning ms-2">Termasuk Nonaktif</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if(isset($total_pages) && $total_pages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm justify-content-end mb-0">
                                    <?php if(isset($current_page) && $current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.($current_page-1).'&search='.urlencode($search).'&filter='.$filter.'&show_nonaktif='.($show_nonaktif?'true':'false')); ?>" tabindex="-1">
                                                Previous
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $start_page = max(1, $current_page - 2);
                                    $end_page = min($total_pages, $current_page + 2);
                                    
                                    if($start_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page=1&search='.urlencode($search).'&filter='.$filter.'&show_nonaktif='.($show_nonaktif?'true':'false')); ?>">1</a>
                                        </li>
                                        <?php if($start_page > 2): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.$i.'&search='.urlencode($search).'&filter='.$filter.'&show_nonaktif='.($show_nonaktif?'true':'false')); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if($end_page < $total_pages): ?>
                                        <?php if($end_page < $total_pages - 1): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.$total_pages.'&search='.urlencode($search).'&filter='.$filter.'&show_nonaktif='.($show_nonaktif?'true':'false')); ?>">
                                                <?php echo $total_pages; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($current_page) && $current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.($current_page+1).'&search='.urlencode($search).'&filter='.$filter.'&show_nonaktif='.($show_nonaktif?'true':'false')); ?>">
                                                Next
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Barang -->
<div class="modal fade" id="detailBarangModal" tabindex="-1" aria-labelledby="detailBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="detailBarangModalLabel">
                    <i class="fas fa-eye me-2"></i>Detail Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailBarangContent">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="tambahBarangModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="tambahBarangModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Barang Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahBarang" method="POST">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Setelah menambahkan barang, Anda dapat menginput stok awal melalui menu "Input Stok Awal" atau di detail barang.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" required 
                                       placeholder="Contoh: TUB001, TIR001" maxlength="20">
                                <div class="form-text">Kode unik untuk barang (max 20 karakter)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required 
                                       placeholder="Nama barang" maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Tube">Tube</option>
                                    <option value="Tire">Tire</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Other">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select" id="satuan" name="satuan" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="PCS" selected>PCS</option>
                                    <option value="SET">SET</option>
                                    <option value="BOX">BOX</option>
                                    <option value="ROLL">ROLL</option>
                                    <option value="METER">METER</option>
                                    <option value="LITER">LITER</option>
                                    <option value="KG">KG</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_minimum" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="stok_minimum" name="stok_minimum" value="5" min="0">
                                <div class="form-text">Stok minimum untuk alert peringatan</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi barang..." maxlength="500"></textarea>
                        <div class="form-text">Maksimal 500 karakter</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-kenda">
                        <i class="fas fa-save me-2"></i>Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Input Stok Awal -->
<div class="modal fade" id="stokAwalModal" tabindex="-1" aria-labelledby="stokAwalModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="stokAwalModalLabel">
                    <i class="fas fa-database me-2"></i>Input Stok Awal Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formStokAwal">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Input stok awal hanya dilakukan sekali saat pertama kali menambahkan barang.
                    </div>
                    
                    <input type="hidden" id="stok_awal_kode_barang_hidden" name="kode_barang">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="stok_awal_kode_barang_display" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="stok_awal_nama_barang" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="stok_awal_kategori" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="stok_awal_satuan" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_awal_jumlah" class="form-label">Jumlah Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stok_awal_jumlah" name="stok_awal" required 
                                       min="0" placeholder="0">
                                <div class="form-text">Masukkan jumlah stok awal barang</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_awal_tanggal" class="form-label">Tanggal Stok Awal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="stok_awal_tanggal" name="tanggal" required 
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stok_awal_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="stok_awal_keterangan" name="keterangan" rows="2" 
                                  placeholder="Keterangan stok awal...">Stok awal barang</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Stok Awal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="editBarangModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editBarangModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditBarang">
                <input type="hidden" id="edit_kode_barang" name="kode_barang">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama_barang" name="nama_barang" required 
                                       placeholder="Nama barang" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="edit_kode_display" readonly>
                                <div class="form-text">Kode barang tidak dapat diubah</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Tube">Tube</option>
                                    <option value="Tire">Tire</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Other">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_satuan" name="satuan" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="PCS">PCS</option>
                                    <option value="SET">SET</option>
                                    <option value="BOX">BOX</option>
                                    <option value="ROLL">ROLL</option>
                                    <option value="METER">METER</option>
                                    <option value="LITER">LITER</option>
                                    <option value="KG">KG</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_stok_minimum" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="edit_stok_minimum" name="stok_minimum" min="0">
                                <div class="form-text">Stok minimum untuk alert peringatan</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi barang..." maxlength="500"></textarea>
                        <div class="form-text">Maksimal 500 karakter</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-kenda">
                        <i class="fas fa-save me-2"></i>Update Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="konfirmasiHapusModal" tabindex="-1" aria-labelledby="konfirmasiHapusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="konfirmasiHapusModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus barang <strong id="namaBarangHapus"></strong>?</p>
                <div id="hapusTypeInfo">
                    <!-- Content akan diisi via JavaScript berdasarkan status barang -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnHapusBarang">
                    <i class="fas fa-trash me-2"></i>Hapus Barang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Aktifkan -->
<div class="modal fade" id="konfirmasiAktifkanModal" tabindex="-1" aria-labelledby="konfirmasiAktifkanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="konfirmasiAktifkanModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Aktifkan Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengaktifkan kembali barang <strong id="namaBarangAktifkan"></strong>?</p>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Barang akan kembali aktif dan dapat digunakan dalam transaksi.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnAktifkanBarang">
                    <i class="fas fa-check me-2"></i>Aktifkan Barang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Barang Masuk -->
<div class="modal fade" id="barangMasukModal" tabindex="-1" aria-labelledby="barangMasukModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="barangMasukModalLabel">
                    <i class="fas fa-arrow-down me-2"></i>Barang Masuk
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBarangMasuk">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Tambah stok barang dari supplier/pembelian.
                    </div>
                    
                    <input type="hidden" id="barang_masuk_kode_barang" name="kode_barang">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="barang_masuk_kode_barang_display" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="barang_masuk_nama_barang" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="barang_masuk_kategori" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="barang_masuk_satuan" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stok Sekarang</label>
                                <input type="text" class="form-control" id="barang_masuk_stok_sekarang" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="barang_masuk_jumlah" class="form-label">Jumlah Masuk <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="barang_masuk_jumlah" name="jumlah" required 
                                       min="1" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="barang_masuk_tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="barang_masuk_tanggal" name="tanggal" required 
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="barang_masuk_supplier" class="form-label">Supplier</label>
                                <input type="text" class="form-control" id="barang_masuk_supplier" name="supplier" 
                                       placeholder="Nama supplier">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="barang_masuk_no_po" class="form-label">No. PO/Invoice</label>
                                <input type="text" class="form-control" id="barang_masuk_no_po" name="no_po" 
                                       placeholder="Nomor PO/Invoice">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="barang_masuk_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="barang_masuk_keterangan" name="keterangan" rows="2" 
                                  placeholder="Keterangan barang masuk..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Simpan Barang Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Barang Keluar -->
<div class="modal fade" id="barangKeluarModal" tabindex="-1" aria-labelledby="barangKeluarModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="barangKeluarModalLabel">
                    <i class="fas fa-arrow-up me-2"></i>Barang Keluar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBarangKeluar">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Pastikan jumlah keluar tidak melebihi stok yang tersedia.
                    </div>
                    
                    <input type="hidden" id="barang_keluar_kode_barang" name="kode_barang">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="barang_keluar_kode_barang_display" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="barang_keluar_nama_barang" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="barang_keluar_kategori" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="barang_keluar_satuan" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stok Tersedia</label>
                                <input type="text" class="form-control" id="barang_keluar_stok_sekarang" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="barang_keluar_jumlah" class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="barang_keluar_jumlah" name="jumlah" required 
                                       min="1" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="barang_keluar_tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="barang_keluar_tanggal" name="tanggal" required 
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="barang_keluar_customer" class="form-label">Customer/Tujuan</label>
                                <input type="text" class="form-control" id="barang_keluar_customer" name="customer" 
                                       placeholder="Nama customer/tujuan">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="barang_keluar_no_sj" class="form-label">No. Surat Jalan</label>
                                <input type="text" class="form-control" id="barang_keluar_no_sj" name="no_sj" 
                                       placeholder="Nomor surat jalan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="barang_keluar_keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="barang_keluar_keterangan" name="keterangan" rows="2" 
                                          placeholder="Keterangan barang keluar..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Simpan Barang Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Adjustment Stok -->
<div class="modal fade" id="adjustmentModal" tabindex="-1" aria-labelledby="adjustmentModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="adjustmentModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i>Adjustment Stok
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAdjustment">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Digunakan untuk mengoreksi stok barang (contoh: stock opname, kerusakan, dll).
                    </div>
                    
                    <input type="hidden" id="adjustment_kode_barang" name="kode_barang">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="adjustment_kode_barang_display" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="adjustment_nama_barang" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="adjustment_kategori" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="adjustment_satuan" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stok Sekarang</label>
                                <input type="text" class="form-control" id="adjustment_stok_sekarang" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="adjustment_stok_baru" class="form-label">Stok Baru <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="adjustment_stok_baru" name="stok_baru" required 
                                       min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="adjustment_alasan" class="form-label">Alasan Adjustment <span class="text-danger">*</span></label>
                                <select class="form-select" id="adjustment_alasan" name="alasan" required>
                                    <option value="">Pilih Alasan</option>
                                    <option value="Stock Opname">Stock Opname</option>
                                    <option value="Kerusakan Barang">Kerusakan Barang</option>
                                    <option value="Kesalahan Pencatatan">Kesalahan Pencatatan</option>
                                    <option value="Sample/Promosi">Sample/Promosi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adjustment_keterangan_lainnya" class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" id="adjustment_keterangan_lainnya" name="keterangan_lainnya" rows="2" 
                                  placeholder="Keterangan tambahan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-2"></i>Simpan Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* STYLE UTAMA TETAP TIDAK DIUBAH */
.info-group {
    border-bottom: 1px solid #f0f0f0;
    padding: 10px 0;
}

.info-group:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-weight: 500;
    font-size: 0.875rem;
}

.info-value {
    color: #212529;
    font-weight: 600;
    font-size: 1rem;
}

.stock-form-container {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.btn-stok-action {
    min-width: 120px;
}

.stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.stat-trend {
    font-size: 0.85rem;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
}

.trend-up {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.trend-down {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-kenda {
    background-color: #0056b3;
    color: white;
    border: none;
}

.btn-kenda:hover {
    background-color: #004494;
    color: white;
}

.btn-kenda-red {
    background-color: #dc3545;
    color: white;
    border: none;
}

.btn-kenda-red:hover {
    background-color: #c82333;
    color: white;
}

.symbol {
    flex-shrink: 0;
}

.symbol-40 {
    width: 40px;
    height: 40px;
}

/* Style khusus untuk modal detail - TAMBAHAN BARU */
/* Card di modal detail */
.detail-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: relative;
}

.detail-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.detail-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #0056b3, #28a745);
    border-radius: 4px 0 0 4px;
}

.detail-card-primary::before {
    background: linear-gradient(to bottom, #0056b3, #17a2b8);
}

.detail-card-success::before {
    background: linear-gradient(to bottom, #28a745, #20c997);
}

.detail-card-warning::before {
    background: linear-gradient(to bottom, #ffc107, #fd7e14);
}

.detail-card-danger::before {
    background: linear-gradient(to bottom, #dc3545, #e83e8c);
}

.detail-card-info::before {
    background: linear-gradient(to bottom, #17a2b8, #6f42c1);
}

/* Card header */
.detail-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.detail-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-card-title i {
    color: #0056b3;
}

.detail-card-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    background: rgba(0, 86, 179, 0.1);
    color: #0056b3;
    border: 1px solid rgba(0, 86, 179, 0.2);
}

/* Card content */
.detail-card-content {
    color: #495057;
    line-height: 1.6;
}

/* Info item di dalam card */
.detail-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.detail-info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.detail-info-item:first-child {
    padding-top: 0;
}

.detail-info-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

.detail-info-value {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1rem;
    text-align: right;
}

/* Stats card untuk stok */
.stats-card {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
}

.stat-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.25rem;
    text-align: center;
    border: 1px solid #e9ecef;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.stat-value.stock-danger {
    color: #dc3545;
}

.stat-value.stock-success {
    color: #28a745;
}

.stat-value.stock-warning {
    color: #ffc107;
}

/* Action buttons card */
.action-grid-card {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
}

.action-btn-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem 1rem;
    text-align: center;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
    cursor: pointer;
    display: block;
}

.action-btn-card:hover {
    background: #f8f9fa;
    border-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 86, 179, 0.1);
    text-decoration: none;
    color: #0056b3;
}

.action-btn-card .action-icon {
    font-size: 1.75rem;
    margin-bottom: 0.75rem;
}

.action-btn-card .action-text {
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.action-btn-card .action-desc {
    font-size: 0.8rem;
    color: #6c757d;
    line-height: 1.4;
}

/* Timestamp card */
.timestamp-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.25rem;
    margin-top: 1.5rem;
}

.timestamp-item {
    margin-bottom: 0.75rem;
}

.timestamp-item:last-child {
    margin-bottom: 0;
}

.timestamp-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.timestamp-value {
    font-size: 0.95rem;
    color: #495057;
    font-weight: 600;
}

/* Khusus untuk modal detail */
#detailBarangContent {
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 0.5rem;
}

#detailBarangContent::-webkit-scrollbar {
    width: 6px;
}

#detailBarangContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#detailBarangContent::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

/* Style untuk barang nonaktif */
tr.table-warning {
    background-color: rgba(255, 193, 7, 0.05) !important;
}

tr.table-warning:hover {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .detail-card {
        padding: 1.25rem;
    }
    
    .stats-card,
    .action-grid-card {
        grid-template-columns: 1fr;
    }
    
    .stat-item {
        padding: 1rem;
    }
    
    .action-btn-card {
        padding: 1.25rem 0.75rem;
    }
}

/* Animasi untuk card muncul */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.detail-card {
    animation: slideUp 0.4s ease forwards;
}

.detail-card:nth-child(2) {
    animation-delay: 0.1s;
}

.detail-card:nth-child(3) {
    animation-delay: 0.2s;
}

.detail-card:nth-child(4) {
    animation-delay: 0.3s;
}
</style>


<script>
// Status constants untuk konsistensi
const SCAN_STATUS = {
    DRAFT: 'draft',
    PRINTED: 'printed',
    SCANNED_OUT: 'scanned_out',
    SCANNED_IN: 'scanned_in',
    COMPLETED: 'completed',
    PENDING: 'pending'
};

const baseUrl = '<?php echo base_url(); ?>';
let items = [];
let editItems = [];
let itemCounter = 0;
let editItemCounter = 0;
let currentDetailPackingId = null;
let currentPackingToDelete = null;
let currentScanPackingId = null;

// Paging variables
let currentPage = 1;
let totalPages = 1;
let pageSize = 10;
let currentFilter = 'all';
let currentSearch = '';

document.addEventListener('DOMContentLoaded', function() {
    loadPackingList();
    loadBarangList();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Select all checkbox
    document.getElementById('selectAllPacking').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.packing-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = document.getElementById('selectAllPacking').checked;
        });
    });

    // Filter functionality
    document.querySelectorAll('.dropdown-item[data-status]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var status = this.getAttribute('data-status');
            currentFilter = status;
            currentPage = 1; // Reset to first page when filter changes
            applyFilter(status);
        });
    });

    // Print labels button
    document.getElementById('printLabelsBtn').addEventListener('click', function() {
        printSelectedLabels();
    });

    // Form submission
    document.getElementById('formBuatPacking').addEventListener('submit', function(e) {
        e.preventDefault();
        simpanPackingList();
    });

    // Edit form submission
    document.getElementById('formEditPacking').addEventListener('submit', function(e) {
        e.preventDefault();
        updatePackingList();
    });

    // Reset form when modal is closed
    document.getElementById('buatPackingModal').addEventListener('hidden.bs.modal', function() {
        resetFormPacking();
    });

    // Reset edit form when modal is closed
    document.getElementById('editPackingModal').addEventListener('hidden.bs.modal', function() {
        resetEditFormPacking();
    });

    // Search functionality
    document.getElementById('searchButton').addEventListener('click', function() {
        performSearch();
    });

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Delete confirmation
    document.getElementById('btnHapusPacking').addEventListener('click', function() {
        hapusPackingList();
    });

    // Load barang list for edit modal when shown
    $('#editPackingModal').on('shown.bs.modal', function() {
        loadBarangList();
    });
});

// ==================== UTILITY FUNCTIONS ====================

function formatTanggal(tanggal) {
    try {
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        return new Date(tanggal).toLocaleDateString('id-ID', options);
    } catch (error) {
        console.error('Error formatting date:', error);
        return 'Invalid Date';
    }
}

function formatTime(timeString) {
    try {
        return new Date(timeString).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    } catch (error) {
        console.error('Error formatting time:', error);
        return 'Invalid Time';
    }
}

function showToast(message, type = 'info') {
    // Buat elemen toast jika belum ada
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
        `;
        document.body.appendChild(toastContainer);
    }
    
    const toastId = 'toast-' + Date.now();
    const bgColor = type === 'success' ? 'bg-success' : 
                    type === 'error' ? 'bg-danger' : 
                    type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const toastHTML = `
        <div id="${toastId}" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${bgColor} text-white">
                <strong class="me-auto">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                  type === 'error' ? 'fa-exclamation-circle' : 
                                  type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                    ${type === 'success' ? 'Sukses' : 
                     type === 'error' ? 'Error' : 
                     type === 'warning' ? 'Peringatan' : 'Info'}
                </strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    // Tambahkan toast
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = toastHTML;
    toastContainer.appendChild(tempDiv.firstElementChild);
    
    // Hapus toast setelah 3 detik
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
}

function showError(message) {
    console.error('Error:', message);
    showToast(message, 'error');
}

function showSuccess(message) {
    console.log('Success:', message);
    showToast(message, 'success');
}

// ==================== PACKING LIST FUNCTIONS ====================

function loadPackingList(page = 1) {
    const params = new URLSearchParams({
        page: page,
        limit: pageSize,
        filter: currentFilter,
        search: currentSearch
    });

    fetch(`${baseUrl}packing_list/api_list_packing?${params}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderPackingTable(data.data);
                if (data.pagination) {
                    updatePagination(data.pagination);
                } else {
                    updatePaginationWithFallback(data.data ? data.data.length : 0, page);
                }
            } else {
                showError('Gagal memuat data packing list');
                updatePaginationWithFallback(0, page);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data');
            updatePaginationWithFallback(0, page);
        });
}

function renderPackingTable(packingList) {
    const tbody = document.getElementById('packingTableBody');
    
    if (!packingList || !Array.isArray(packingList)) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <p>Terjadi kesalahan dalam memuat data</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    if (packingList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>Belum ada packing list</p>
                        <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#buatPackingModal">
                            <i class="fas fa-plus me-2"></i>Buat Packing List Pertama
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    const startNumber = (currentPage - 1) * pageSize + 1;
    
    packingList.forEach((packing, index) => {
        if (!packing) return;
        
        const scanOutStatus = getScanOutStatus(packing.status_scan_out);
        const scanInStatus = getScanInStatus(packing.status_scan_in);
        
        html += `
            <tr data-status-scan-out="${packing.status_scan_out || ''}" data-status-scan-in="${packing.status_scan_in || ''}">
                <td>
                    <input type="checkbox" class="packing-checkbox" value="${packing.id || ''}">
                </td>
                <td>${startNumber + index}</td>
                <td>
                    <span class="badge bg-dark">${packing.no_packing || 'N/A'}</span>
                </td>
                <td>${packing.tanggal ? formatTanggal(packing.tanggal) : 'N/A'}</td>
                <td>${packing.customer || 'N/A'}</td>
                <td>
                    <span class="fw-bold">${packing.jumlah_item || 0} item</span>
                </td>
                <td>
                    <span class="badge ${scanOutStatus.class}">
                        <i class="${scanOutStatus.icon} me-1"></i>${scanOutStatus.text}
                    </span>
                    ${packing.scan_out_time ? `<br><small class="text-muted">${formatTime(packing.scan_out_time)}</small>` : ''}
                </td>
                <td>
                    <span class="badge ${scanInStatus.class}">
                        <i class="${scanInStatus.icon} me-1"></i>${scanInStatus.text}
                    </span>
                    ${packing.scan_in_time ? `<br><small class="text-muted">${formatTime(packing.scan_in_time)}</small>` : ''}
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary" 
                                onclick="detailPacking(${packing.id || 0})"
                                data-bs-toggle="tooltip" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning"
                                onclick="editPacking(${packing.id || 0})"
                                data-bs-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success"
                                onclick="printPackingLabel(${packing.id || 0}, '${packing.no_packing || ''}')"
                                data-bs-toggle="tooltip" title="Cetak Label">
                            <i class="fas fa-print"></i>
                        </button>
                        <button type="button" class="btn btn-outline-info"
                                onclick="showScanActions(${packing.id || 0}, '${packing.no_packing || ''}')"
                                data-bs-toggle="tooltip" title="Scan Actions">
                            <i class="fas fa-qrcode"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger"
                                onclick="konfirmasiHapus(${packing.id || 0}, '${packing.no_packing || ''}')"
                                data-bs-toggle="tooltip" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    
    // Re-initialize tooltips for new elements
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function getScanOutStatus(status) {
    const statusMap = {
        [SCAN_STATUS.DRAFT]: { text: 'Draft', class: 'bg-secondary', icon: 'fas fa-file' },
        [SCAN_STATUS.PRINTED]: { text: 'Label Tercetak', class: 'bg-warning', icon: 'fas fa-print' },
        [SCAN_STATUS.SCANNED_OUT]: { text: 'Terkirim', class: 'bg-success', icon: 'fas fa-check-circle' },
        [SCAN_STATUS.SCANNED_IN]: { text: 'Loading', class: 'bg-primary', icon: 'fas fa-truck-loading' }
    };
    return statusMap[status] || { text: 'Unknown', class: 'bg-secondary', icon: 'fas fa-question-circle' };
}

function getScanInStatus(status) {
    const statusMap = {
        [SCAN_STATUS.PENDING]: { text: 'Belum Loading', class: 'bg-secondary', icon: 'fas fa-clock' },
        [SCAN_STATUS.SCANNED_IN]: { text: 'Loading', class: 'bg-primary', icon: 'fas fa-truck-loading' },
        [SCAN_STATUS.COMPLETED]: { text: 'Selesai', class: 'bg-success', icon: 'fas fa-flag-checkered' }
    };
    return statusMap[status] || { text: 'Unknown', class: 'bg-secondary', icon: 'fas fa-question-circle' };
}

// ==================== PAGINATION FUNCTIONS ====================

function updatePagination(pagination) {
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationInfo = document.getElementById('paginationInfo');
    
    if (!pagination || typeof pagination.total === 'undefined') {
        console.warn('Data pagination tidak valid:', pagination);
        updatePaginationWithFallback(0, currentPage);
        return;
    }
    
    const startItem = (currentPage - 1) * pageSize + 1;
    const endItem = Math.min(currentPage * pageSize, pagination.total);
    paginationInfo.innerHTML = `Menampilkan <strong>${startItem}-${endItem}</strong> dari <strong>${pagination.total}</strong> packing list`;
    
    let paginationHtml = '';
    totalPages = pagination.total_pages || 1;
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    // Previous button
    paginationHtml += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // First page
    if (startPage > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(1); return false;">1</a>
            </li>
            ${startPage > 2 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
        `;
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    // Last page
    if (endPage < totalPages) {
        paginationHtml += `
            ${endPage < totalPages - 1 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a>
            </li>
        `;
    }
    
    // Next button
    paginationHtml += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
    
    paginationContainer.innerHTML = paginationHtml;
}

function updatePaginationWithFallback(totalItems, currentPage) {
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationInfo = document.getElementById('paginationInfo');
    
    totalPages = Math.ceil(totalItems / pageSize) || 1;
    
    const startItem = (currentPage - 1) * pageSize + 1;
    const endItem = Math.min(currentPage * pageSize, totalItems);
    
    if (totalItems > 0) {
        paginationInfo.innerHTML = `Menampilkan <strong>${startItem}-${endItem}</strong> dari <strong>${totalItems}</strong> packing list`;
    } else {
        paginationInfo.innerHTML = 'Tidak ada data yang ditampilkan';
    }
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let paginationHtml = '';
    
    // Previous button
    paginationHtml += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Page numbers (sederhana, hanya tampilkan 3 halaman sekitar current page)
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Next button
    paginationHtml += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
    
    paginationContainer.innerHTML = paginationHtml;
}

function changePage(page) {
    if (page < 1 || page > totalPages || page === currentPage) return;
    
    currentPage = page;
    loadPackingList(currentPage);
    
    // Scroll to top of table
    document.getElementById('packingTable').scrollIntoView({ behavior: 'smooth' });
}

// ==================== SEARCH & FILTER FUNCTIONS ====================

function performSearch() {
    currentSearch = document.getElementById('searchInput').value.trim();
    currentPage = 1; // Reset to first page when searching
    loadPackingList(currentPage);
}

function applyFilter(status) {
    currentFilter = status;
    currentPage = 1; // Reset to first page when filter changes
    loadPackingList(currentPage);
    
    // Update dropdown text
    const dropdownButton = document.querySelector('.dropdown-toggle');
    const statusText = getStatusText(status);
    dropdownButton.innerHTML = `<i class="fas fa-filter me-2"></i>${statusText}`;
}

function getStatusText(status) {
    const statusMap = {
        'all': 'Semua Status',
        'draft': 'Draft',
        'printed': 'Label Tercetak',
        'scanned_out': 'Terkirim',
        'scanned_in': 'Loading',
        'completed': 'Selesai Loading'
    };
    return statusMap[status] || 'Filter Status';
}

// ==================== BARANG MANAGEMENT FUNCTIONS ====================

function loadBarangList() {
    fetch(`${baseUrl}packing_list/api_get_barang`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderBarangDropdown(data.data);
                renderEditBarangDropdown(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading barang list:', error);
            showError('Gagal memuat data barang');
        });
}

function renderBarangDropdown(barangList) {
    const select = document.getElementById('pilihBarang');
    
    if (!barangList || !Array.isArray(barangList)) {
        select.innerHTML = '<option value="">Gagal memuat data barang</option>';
        return;
    }
    
    let html = '<option value="">Pilih Barang...</option>';
    
    barangList.forEach(barang => {
        if (!barang) return;
        html += `<option value="${barang.id || ''}" 
                         data-kode="${barang.kode || ''}" 
                         data-nama="${barang.nama || ''}" 
                         data-kategori="${barang.kategori || ''}">
                    ${barang.kode || ''} - ${barang.nama || ''} (Stok: ${barang.stok || 0})
                </option>`;
    });
    
    select.innerHTML = html;
}

function renderEditBarangDropdown(barangList) {
    const select = document.getElementById('edit_pilihBarang');
    
    if (!barangList || !Array.isArray(barangList)) {
        select.innerHTML = '<option value="">Gagal memuat data barang</option>';
        return;
    }
    
    let html = '<option value="">Pilih Barang...</option>';
    
    barangList.forEach(barang => {
        if (!barang) return;
        html += `<option value="${barang.id || ''}" 
                         data-kode="${barang.kode || ''}" 
                         data-nama="${barang.nama || ''}" 
                         data-kategori="${barang.kategori || ''}">
                    ${barang.kode || ''} - ${barang.nama || ''} (Stok: ${barang.stok || 0})
                </option>`;
    });
    
    select.innerHTML = html;
}

function tambahItem() {
    const selectBarang = document.getElementById('pilihBarang');
    const qtyInput = document.getElementById('qtyBarang');
    
    if (selectBarang.value === '') {
        showToast('Pilih barang terlebih dahulu!', 'warning');
        return;
    }
    
    if (qtyInput.value === '' || parseInt(qtyInput.value) < 1) {
        showToast('Masukkan quantity yang valid!', 'warning');
        return;
    }
    
    const selectedOption = selectBarang.options[selectBarang.selectedIndex];
    const kodeBarang = selectedOption.getAttribute('data-kode');
    const namaBarang = selectedOption.getAttribute('data-nama');
    const kategori = selectedOption.getAttribute('data-kategori');
    const qty = parseInt(qtyInput.value);
    
    // Check stock availability
    const stockText = selectedOption.textContent.match(/Stok:\s*(\d+)/);
    const stock = stockText ? parseInt(stockText[1]) : 0;
    
    if (qty > stock) {
        showToast(`Stok tidak mencukupi! Stok tersedia: ${stock}`, 'warning');
        return;
    }
    
    // Check if item already exists
    const existingItem = items.find(item => item.kode === kodeBarang);
    if (existingItem) {
        const newQty = parseInt(existingItem.qty) + qty;
        if (newQty > stock) {
            showToast(`Stok tidak mencukupi untuk penambahan! Stok tersedia: ${stock}`, 'warning');
            return;
        }
        existingItem.qty = newQty;
        updateTabelItem();
    } else {
        items.push({
            id: itemCounter++,
            kode: kodeBarang,
            nama: namaBarang,
            kategori: kategori,
            qty: qty
        });
        updateTabelItem();
    }
    
    // Reset form
    selectBarang.value = '';
    qtyInput.value = '1';
}

function tambahItemEdit() {
    const selectBarang = document.getElementById('edit_pilihBarang');
    const qtyInput = document.getElementById('edit_qtyBarang');
    
    if (selectBarang.value === '') {
        showToast('Pilih barang terlebih dahulu!', 'warning');
        return;
    }
    
    if (qtyInput.value === '' || parseInt(qtyInput.value) < 1) {
        showToast('Masukkan quantity yang valid!', 'warning');
        return;
    }
    
    const selectedOption = selectBarang.options[selectBarang.selectedIndex];
    const kodeBarang = selectedOption.getAttribute('data-kode');
    const namaBarang = selectedOption.getAttribute('data-nama');
    const kategori = selectedOption.getAttribute('data-kategori');
    const qty = parseInt(qtyInput.value);
    
    // Check if item already exists
    const existingItem = editItems.find(item => item.kode === kodeBarang);
    if (existingItem) {
        existingItem.qty = parseInt(existingItem.qty) + qty;
        updateEditTabelItem();
    } else {
        editItems.push({
            id: editItemCounter++,
            kode: kodeBarang,
            nama: namaBarang,
            kategori: kategori,
            qty: qty
        });
        updateEditTabelItem();
    }
    
    // Reset form
    selectBarang.value = '';
    qtyInput.value = '1';
}

function hapusItem(id) {
    items = items.filter(item => item.id !== id);
    updateTabelItem();
}

function hapusItemEdit(id) {
    editItems = editItems.filter(item => item.id !== id);
    updateEditTabelItem();
}

function updateTabelItem() {
    const tbody = document.getElementById('bodyItem');
    const totalQty = document.getElementById('totalQty');
    
    tbody.innerHTML = '';
    let total = 0;
    
    items.forEach(item => {
        const qty = parseInt(item.qty) || 0;
        total += qty;
        
        tbody.innerHTML += `
            <tr>
                <td>${item.id + 1}</td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>
                    <span class="badge ${item.kategori === 'Tube' ? 'bg-primary' : 'bg-success'}">
                        ${item.kategori}
                    </span>
                </td>
                <td class="text-end">${qty}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusItem(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    totalQty.textContent = total;
}

function updateEditTabelItem() {
    const tbody = document.getElementById('edit_bodyItem');
    const totalQty = document.getElementById('edit_totalQty');
    
    tbody.innerHTML = '';
    let total = 0;
    
    editItems.forEach(item => {
        const qty = parseInt(item.qty) || 0;
        total += qty;
        
        tbody.innerHTML += `
            <tr>
                <td>${item.id + 1}</td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>
                    <span class="badge ${item.kategori === 'Tube' ? 'bg-primary' : 'bg-success'}">
                        ${item.kategori}
                    </span>
                </td>
                <td class="text-end">${qty}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusItemEdit(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    totalQty.textContent = total;
}

// ==================== PACKING LIST CRUD FUNCTIONS ====================

function simpanPackingList() {
    if (items.length === 0) {
        showToast('Tambahkan minimal satu item!', 'warning');
        return;
    }
    
    const formData = new FormData(document.getElementById('formBuatPacking'));
    formData.append('items', JSON.stringify(items));
    
    // Show loading
    const submitBtn = document.querySelector('#formBuatPacking button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    fetch(`${baseUrl}packing_list/simpan_packing`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccess('Packing list berhasil disimpan!');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('buatPackingModal'));
            if (modal) modal.hide();
            loadPackingList();
            resetFormPacking();
        } else {
            showError('Error: ' + (data.message || 'Gagal menyimpan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menyimpan packing list');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function updatePackingList() {
    if (editItems.length === 0) {
        showToast('Tambahkan minimal satu item!', 'warning');
        return;
    }
    
    const formData = new FormData(document.getElementById('formEditPacking'));
    formData.append('items', JSON.stringify(editItems));
    
    // Show loading
    const submitBtn = document.querySelector('#formEditPacking button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
    submitBtn.disabled = true;
    
    fetch(`${baseUrl}packing_list/update_packing`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccess('Packing list berhasil diupdate!');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('editPackingModal'));
            if (modal) modal.hide();
            loadPackingList();
            resetEditFormPacking();
        } else {
            showError('Error: ' + (data.message || 'Gagal mengupdate'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat mengupdate packing list');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function resetFormPacking() {
    items = [];
    itemCounter = 0;
    updateTabelItem();
    document.getElementById('formBuatPacking').reset();
    // Generate new packing number
    fetch(`${baseUrl}packing_list/generate_packing_number`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('no_packing').value = data.data.no_packing;
            }
        });
    document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];
}

function resetEditFormPacking() {
    editItems = [];
    editItemCounter = 0;
    updateEditTabelItem();
}

function editPacking(id) {
    fetch(`${baseUrl}packing_list/api_detail_packing/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            } else {
                showError('Gagal memuat data packing list untuk edit');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data untuk edit');
        });
}

function showEditModal(data) {
    if (!data) {
        showError('Data packing list tidak valid');
        return;
    }

    document.getElementById('edit_id').value = data.id || '';
    document.getElementById('edit_no_packing').value = data.no_packing || '';
    document.getElementById('edit_tanggal').value = data.tanggal || '';
    document.getElementById('edit_customer').value = data.customer || '';
    document.getElementById('edit_alamat').value = data.alamat || '';
    document.getElementById('edit_keterangan').value = data.keterangan || '';
    
    editItems = [];
    editItemCounter = 0;
    
    if (data.items && Array.isArray(data.items)) {
        data.items.forEach((item, index) => {
            const qty = parseInt(item.qty) || 0;
            editItems.push({
                id: editItemCounter++,
                kode: item.kode || '',
                nama: item.nama || '',
                kategori: item.kategori || '',
                qty: qty
            });
        });
    }
    
    updateEditTabelItem();
    
    const modal = new bootstrap.Modal(document.getElementById('editPackingModal'));
    modal.show();
}

function detailPacking(id) {
    fetch(`${baseUrl}packing_list/api_detail_packing/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showDetailModal(data.data);
                currentDetailPackingId = id;
            } else {
                showError('Gagal memuat detail packing list');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat detail');
        });
}

function showDetailModal(data) {
    if (!data) {
        document.getElementById('detailPackingContent').innerHTML = `
            <div class="text-center py-4">
                <div class="text-danger">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <p>Gagal memuat detail packing list</p>
                </div>
            </div>
        `;
        return;
    }

    const scanOutStatus = getScanOutStatus(data.status_scan_out);
    const scanInStatus = getScanInStatus(data.status_scan_in);
    
    // Hitung total items dari data.items
    let totalItems = 0;
    let itemsHtml = '';
    if (data.items && Array.isArray(data.items)) {
        data.items.forEach((item, index) => {
            const qty = parseInt(item.qty) || 0;
            totalItems += qty;
            itemsHtml += `
                <tr>
                    <td class="text-muted">${index + 1}</td>
                    <td>
                        <span class="badge bg-dark">${item.kode || 'N/A'}</span>
                    </td>
                    <td class="fw-medium">${item.nama || 'N/A'}</td>
                    <td>
                        <span class="badge ${(item.kategori === 'Tube' ? 'bg-primary' : 'bg-success')}">
                            ${item.kategori || 'N/A'}
                        </span>
                    </td>
                    <td class="text-end fw-bold">${qty}</td>
                </tr>
            `;
        });
    } else {
        itemsHtml = '<tr><td colspan="5" class="text-center">Tidak ada data item</td></tr>';
    }
    
    const modalContent = `
        <div class="row">
            <div class="col-md-6">
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">No. Packing List</label>
                    <div class="fw-bold text-dark">${data.no_packing || 'N/A'}</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Tanggal</label>
                    <div class="fw-bold text-dark">${data.tanggal ? formatTanggal(data.tanggal) : 'N/A'}</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Customer</label>
                    <div class="fw-bold text-dark">${data.customer || 'N/A'}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Alamat Pengiriman</label>
                    <div class="text-dark">${data.alamat || '<span class="text-muted">-</span>'}</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Status Scan Out</label>
                    <div>
                        <span class="badge ${scanOutStatus.class}">
                            <i class="${scanOutStatus.icon} me-1"></i>${scanOutStatus.text}
                        </span>
                        ${data.scan_out_time ? `<br><small class="text-muted">${formatTime(data.scan_out_time)}</small>` : ''}
                    </div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Status Scan In</label>
                    <div>
                        <span class="badge ${scanInStatus.class}">
                            <i class="${scanInStatus.icon} me-1"></i>${scanInStatus.text}
                        </span>
                        ${data.scan_in_time ? `<br><small class="text-muted">${formatTime(data.scan_in_time)}</small>` : ''}
                    </div>
                </div>
            </div>
        </div>
        
        ${data.keterangan ? `
        <div class="row mt-3">
            <div class="col-12">
                <div class="info-group">
                    <label class="form-label text-muted small mb-1">Keterangan</label>
                    <div class="text-dark">${data.keterangan}</div>
                </div>
            </div>
        </div>
        ` : ''}
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="section-header mb-3">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Daftar Item (${totalItems} item)
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th width="100">Kategori</th>
                                <th width="80" class="text-end">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold text-primary">${totalItems}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-light border">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-kenda-red me-3 fa-lg"></i>
                        <div>
                            <small class="text-muted">
                                Packing list dibuat pada ${data.tanggal ? formatTanggal(data.tanggal) : 'N/A'}
                                ${data.scan_out_time ? ` • Scan Out: ${formatTime(data.scan_out_time)}` : ''}
                                ${data.scan_in_time ? ` • Scan In: ${formatTime(data.scan_in_time)}` : ''}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('detailPackingContent').innerHTML = modalContent;
    document.getElementById('detailPackingModalLabel').innerHTML = `<i class="fas fa-eye me-2"></i>Detail - ${data.no_packing || 'N/A'}`;
    
    const modal = new bootstrap.Modal(document.getElementById('detailPackingModal'));
    modal.show();
}

function konfirmasiHapus(id, noPacking) {
    currentPackingToDelete = id;
    document.getElementById('namaPackingHapus').textContent = noPacking;
    
    const modal = new bootstrap.Modal(document.getElementById('konfirmasiHapusModal'));
    modal.show();
}

function hapusPackingList() {
    if (!currentPackingToDelete) {
        showToast('Tidak ada packing list yang dipilih untuk dihapus', 'warning');
        return;
    }
    
    fetch(`${baseUrl}packing_list/delete_packing/${currentPackingToDelete}`, {
        method: 'DELETE',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccess('Packing list berhasil dihapus!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('konfirmasiHapusModal'));
            if (modal) modal.hide();
            loadPackingList();
        } else {
            showError('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menghapus packing list');
    })
    .finally(() => {
        currentPackingToDelete = null;
    });
}

// ==================== PRINT & LABEL FUNCTIONS ====================

function printPackingLabel(packingId, noPacking) {
    // Redirect ke halaman pilih format label
    window.location.href = `${baseUrl}packing_list/pilih_format/${packingId}`;
}

function printPackingList(packingId) {
    const printUrl = `${baseUrl}packing_list/cetak/${packingId}`;
    window.open(printUrl, '_blank');
}

function printSelectedLabels() {
    const selected = document.querySelectorAll('.packing-checkbox:checked');
    if (selected.length === 0) {
        showToast('Pilih packing list yang akan dicetak labelnya!', 'warning');
        return;
    }
    
    const packingIds = Array.from(selected).map(checkbox => {
        return parseInt(checkbox.value);
    }).filter(id => !isNaN(id));
    
    if (packingIds.length === 0) {
        showToast('Tidak ada packing list valid yang dipilih', 'warning');
        return;
    }
    
    // Tampilkan dialog pilihan format
    showPrintFormatDialog(packingIds);
}

function showPrintFormatDialog(packingIds) {
    const dialogHTML = `
        <div class="modal fade" id="printFormatModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-print me-2"></i>Pilih Format Cetak
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Pilih format cetak untuk <strong>${packingIds.length}</strong> packing list:</p>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Cetakan</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="printType" id="printTypeLabel" value="label" checked>
                                <label class="btn btn-outline-primary" for="printTypeLabel">Label</label>
                                
                                <input type="radio" class="btn-check" name="printType" id="printTypeDocument" value="document">
                                <label class="btn btn-outline-primary" for="printTypeDocument">Dokumen</label>
                            </div>
                        </div>
                        
                        <div id="labelFormatSection">
                            <label class="form-label">Format Label</label>
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="card text-center format-option" data-format="kenda">
                                        <div class="card-body p-2">
                                            <i class="fas fa-tag text-danger mb-1"></i>
                                            <small class="d-block">KENDA</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card text-center format-option" data-format="standard">
                                        <div class="card-body p-2">
                                            <i class="fas fa-barcode text-primary mb-1"></i>
                                            <small class="d-block">Standard</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card text-center format-option" data-format="simple">
                                        <div class="card-body p-2">
                                            <i class="fas fa-tag text-success mb-1"></i>
                                            <small class="d-block">Simple</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="autoPrint" checked>
                                <label class="form-check-label" for="autoPrint">
                                    Auto print setelah membuka window
                                </label>
                            </div>
                        </div>
                        
                        <div id="documentFormatSection" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Dokumen akan dicetak satu per satu
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirmPrintBtn">
                            <i class="fas fa-print me-2"></i>Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    const modalDiv = document.createElement('div');
    modalDiv.innerHTML = dialogHTML;
    document.body.appendChild(modalDiv);
    
    const modal = new bootstrap.Modal(document.getElementById('printFormatModal'));
    modal.show();
    
    let selectedFormat = 'kenda';
    let printType = 'label';
    
    // Format selection
    document.querySelectorAll('.format-option').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.format-option').forEach(c => {
                c.classList.remove('border-primary');
            });
            this.classList.add('border-primary');
            selectedFormat = this.getAttribute('data-format');
        });
    });
    
    // Print type selection
    document.querySelectorAll('input[name="printType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            printType = this.value;
            if (printType === 'label') {
                document.getElementById('labelFormatSection').style.display = 'block';
                document.getElementById('documentFormatSection').style.display = 'none';
            } else {
                document.getElementById('labelFormatSection').style.display = 'none';
                document.getElementById('documentFormatSection').style.display = 'block';
            }
        });
    });
    
    // Set default selected
    document.querySelector('.format-option[data-format="kenda"]').classList.add('border-primary');
    
    // Confirm print
    document.getElementById('confirmPrintBtn').addEventListener('click', function() {
        modal.hide();
        
        if (printType === 'label') {
            // Cetak label multiple
            const autoPrint = document.getElementById('autoPrint').checked ? '1' : '0';
            const printUrl = `${baseUrl}packing_list/cetak_label_multiple?ids=${packingIds.join(',')}&format=${selectedFormat}&autoprint=${autoPrint}`;
            
            // Open print window
            const printWindow = window.open(printUrl, '_blank', 'width=1200,height=800');
            
            // Update status setelah cetak
            updateBatchPackingStatus(packingIds, SCAN_STATUS.PRINTED);
        } else {
            // Cetak dokumen satu per satu
            packingIds.forEach((id, index) => {
                setTimeout(() => {
                    const printUrl = `${baseUrl}packing_list/cetak/${id}`;
                    window.open(printUrl, '_blank');
                }, index * 500); // Delay untuk mencegah popup blocker
            });
        }
        
        // Remove modal after use
        setTimeout(() => {
            modalDiv.remove();
        }, 1000);
    });
    
    // Remove modal when hidden
    document.getElementById('printFormatModal').addEventListener('hidden.bs.modal', function() {
        setTimeout(() => {
            if (modalDiv.parentNode) {
                modalDiv.remove();
            }
        }, 300);
    });
}

function printLabelFromDetail() {
    if (currentDetailPackingId) {
        printPackingLabel(currentDetailPackingId, '');
        
        // Tutup modal detail
        const modal = bootstrap.Modal.getInstance(document.getElementById('detailPackingModal'));
        if (modal) {
            modal.hide();
        }
    } else {
        showToast('Tidak ada packing list yang dipilih', 'warning');
    }
}

// ==================== SCAN FUNCTIONS ====================

function showScanActions(packingId, noPacking) {
    currentScanPackingId = packingId;
    
    fetch(`${baseUrl}packing_list/api_detail_packing/${packingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showScanActionsModal(data.data, noPacking);
            } else {
                showError('Gagal memuat data packing list untuk scan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data untuk scan');
        });
}

function showScanActionsModal(data, noPacking) {
    const scanOutStatus = getScanOutStatus(data.status_scan_out);
    const scanInStatus = getScanInStatus(data.status_scan_in);
    
    // Dapatkan valid actions berdasarkan status saat ini
    const validActions = getNextValidActions(data.status_scan_out, data.status_scan_in);
    
    const modalContent = `
        <div class="text-center mb-4">
            <h6 class="fw-bold">${noPacking}</h6>
            <p class="text-muted mb-0">${data.customer || 'N/A'}</p>
            <small class="text-muted">${data.jumlah_item || 0} items</small>
        </div>
        
        <div class="row mb-4">
            <div class="col-6">
                <div class="text-center">
                    <label class="form-label text-muted small mb-2">Status Scan Out</label>
                    <div>
                        <span class="badge ${scanOutStatus.class}">
                            <i class="${scanOutStatus.icon} me-1"></i>${scanOutStatus.text}
                        </span>
                    </div>
                    ${data.scan_out_time ? `<small class="text-muted d-block mt-1">${formatTime(data.scan_out_time)}</small>` : ''}
                </div>
            </div>
            <div class="col-6">
                <div class="text-center">
                    <label class="form-label text-muted small mb-2">Status Scan In</label>
                    <div>
                        <span class="badge ${scanInStatus.class}">
                            <i class="${scanInStatus.icon} me-1"></i>${scanInStatus.text}
                        </span>
                    </div>
                    ${data.scan_in_time ? `<small class="text-muted d-block mt-1">${formatTime(data.scan_in_time)}</small>` : ''}
                </div>
            </div>
        </div>
        
        <div class="scan-actions-container">
            <!-- Scan Out Actions -->
            <div class="mb-3">
                <h6 class="section-title mb-3">
                    <i class="fas fa-sign-out-alt me-2"></i>Scan Keluar Gudang
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn ${validActions.canScanOut ? 'btn-outline-primary' : 'btn-outline-secondary'} w-100 scan-action-btn" 
                                onclick="${validActions.canScanOut ? `scanOutGudang(${data.id})` : ''}" 
                                ${validActions.canScanOut ? '' : 'disabled'}>
                            <i class="fas fa-door-open fa-lg ${validActions.canScanOut ? 'text-primary' : 'text-secondary'}"></i>
                            <div class="mt-1">
                                <small class="fw-bold">Scan Out</small>
                                <br>
                                <small class="text-muted">Keluar Gudang</small>
                            </div>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn ${validActions.canUndoScanOut ? 'btn-outline-success' : 'btn-outline-secondary'} w-100 scan-action-btn" 
                                onclick="${validActions.canUndoScanOut ? `undoScanOut(${data.id})` : ''}" 
                                ${validActions.canUndoScanOut ? '' : 'disabled'}>
                            <i class="fas fa-undo fa-lg ${validActions.canUndoScanOut ? 'text-success' : 'text-secondary'}"></i>
                            <div class="mt-1">
                                <small class="fw-bold">Undo Scan Out</small>
                                <br>
                                <small class="text-muted">Batalkan Keluar</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Scan In Actions -->
            <div class="mb-3">
                <h6 class="section-title mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Scan Loading Kendaraan
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn ${validActions.canScanIn ? 'btn-outline-info' : 'btn-outline-secondary'} w-100 scan-action-btn" 
                                onclick="${validActions.canScanIn ? `scanInKendaraan(${data.id})` : ''}" 
                                ${validActions.canScanIn ? '' : 'disabled'}>
                            <i class="fas fa-truck-loading fa-lg ${validActions.canScanIn ? 'text-info' : 'text-secondary'}"></i>
                            <div class="mt-1">
                                <small class="fw-bold">Scan In</small>
                                <br>
                                <small class="text-muted">Loading Kendaraan</small>
                            </div>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn ${validActions.canComplete ? 'btn-outline-warning' : 'btn-outline-secondary'} w-100 scan-action-btn" 
                                onclick="${validActions.canComplete ? `completeLoading(${data.id})` : ''}" 
                                ${validActions.canComplete ? '' : 'disabled'}>
                            <i class="fas fa-check-circle fa-lg ${validActions.canComplete ? 'text-warning' : 'text-secondary'}"></i>
                            <div class="mt-1">
                                <small class="fw-bold">Selesai</small>
                                <br>
                                <small class="text-muted">Loading Selesai</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Status Timeline -->
            <div class="mt-4 p-3 bg-light rounded">
                <h6 class="section-title mb-3">
                    <i class="fas fa-list-ol me-2"></i>Status Timeline
                </h6>
                <div class="status-timeline">
                    <div class="timeline-item ${data.status_scan_out === SCAN_STATUS.PRINTED ? 'active' : ''} ${data.status_scan_out === SCAN_STATUS.SCANNED_OUT || data.status_scan_out === SCAN_STATUS.SCANNED_IN ? 'completed' : ''}">
                        <small class="fw-bold">Label Tercetak</small>
                        <br>
                        <small class="text-muted">Packing list sudah dicetak</small>
                    </div>
                    <div class="timeline-item ${data.status_scan_out === SCAN_STATUS.SCANNED_OUT ? 'active' : ''} ${data.status_scan_out === SCAN_STATUS.SCANNED_IN ? 'completed' : ''}">
                        <small class="fw-bold">Scan Out Gudang</small>
                        <br>
                        <small class="text-muted">Barang keluar dari gudang</small>
                    </div>
                    <div class="timeline-item ${data.status_scan_in === SCAN_STATUS.SCANNED_IN ? 'active' : ''} ${data.status_scan_in === SCAN_STATUS.COMPLETED ? 'completed' : ''}">
                        <small class="fw-bold">Scan In Kendaraan</small>
                        <br>
                        <small class="text-muted">Barang loading ke kendaraan</small>
                    </div>
                    <div class="timeline-item ${data.status_scan_in === SCAN_STATUS.COMPLETED ? 'active completed' : ''}">
                        <small class="fw-bold">Selesai Loading</small>
                        <br>
                        <small class="text-muted">Proses loading selesai</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Urutan proses: Print Label → Scan Out → Scan In → Complete
            </small>
        </div>
    `;
    
    document.getElementById('scanActionsContent').innerHTML = modalContent;
    document.getElementById('scanActionsModalLabel').innerHTML = `<i class="fas fa-qrcode me-2"></i>Scan - ${noPacking}`;
    
    const modal = new bootstrap.Modal(document.getElementById('scanActionsModal'));
    modal.show();
}

function getNextValidActions(statusScanOut, statusScanIn) {
    const actions = {
        canScanOut: statusScanOut === SCAN_STATUS.PRINTED && statusScanIn === SCAN_STATUS.PENDING,
        canUndoScanOut: statusScanOut === SCAN_STATUS.SCANNED_OUT && statusScanIn === SCAN_STATUS.PENDING,
        canScanIn: statusScanOut === SCAN_STATUS.SCANNED_OUT && statusScanIn === SCAN_STATUS.PENDING,
        canComplete: statusScanOut === SCAN_STATUS.SCANNED_OUT && statusScanIn === SCAN_STATUS.SCANNED_IN,
        canUndoScanIn: statusScanIn === SCAN_STATUS.SCANNED_IN
    };
    
    return actions;
}

async function scanOutGudang(packingId) {
    if (!confirm('Konfirmasi Scan Out: Packing list akan dikonfirmasi keluar dari gudang?')) {
        return;
    }
    
    try {
        const response = await fetch(`${baseUrl}packing_list/api_scan_out`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `packing_id=${packingId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Scan Out berhasil! Packing list telah dikonfirmasi keluar gudang.');
            // Close modal and refresh data
            const modal = bootstrap.Modal.getInstance(document.getElementById('scanActionsModal'));
            if (modal) modal.hide();
            loadPackingList();
        } else {
            showError('Gagal melakukan Scan Out: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat melakukan Scan Out');
    }
}

async function undoScanOut(packingId) {
    if (!confirm('Konfirmasi Undo Scan Out: Packing list akan dikembalikan ke status sebelumnya?')) {
        return;
    }
    
    try {
        const response = await fetch(`${baseUrl}packing_list/api_undo_scan_out`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `packing_id=${packingId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Undo Scan Out berhasil!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('scanActionsModal'));
            if (modal) modal.hide();
            loadPackingList();
        } else {
            showError('Gagal undo Scan Out: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat undo Scan Out');
    }
}

async function scanInKendaraan(packingId) {
    if (!confirm('Konfirmasi Scan In: Packing list akan dikonfirmasi loading ke kendaraan?')) {
        return;
    }
    
    try {
        const response = await fetch(`${baseUrl}packing_list/api_scan_in`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `packing_id=${packingId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Scan In berhasil! Packing list telah dikonfirmasi loading ke kendaraan.');
            const modal = bootstrap.Modal.getInstance(document.getElementById('scanActionsModal'));
            if (modal) modal.hide();
            loadPackingList();
        } else {
            showError('Gagal melakukan Scan In: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat melakukan Scan In');
    }
}

async function completeLoading(packingId) {
    if (!confirm('Konfirmasi Selesai Loading: Packing list akan ditandai sebagai selesai loading?')) {
        return;
    }
    
    try {
        const response = await fetch(`${baseUrl}packing_list/api_complete_loading`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `packing_id=${packingId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Loading selesai! Packing list telah selesai diproses.');
            const modal = bootstrap.Modal.getInstance(document.getElementById('scanActionsModal'));
            if (modal) modal.hide();
            loadPackingList();
        } else {
            showError('Gagal menandai selesai loading: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menandai selesai loading');
    }
}

// ==================== STATUS UPDATE FUNCTIONS ====================

function updatePackingStatus(packingId, status) {
    fetch(`${baseUrl}packing_list/api_update_status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `packing_id=${packingId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`Status packing ${packingId} diupdate ke '${status}'`);
            // Refresh data setelah update
            setTimeout(() => loadPackingList(), 1000);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

function updateBatchPackingStatus(packingIds, status) {
    fetch(`${baseUrl}packing_list/api_update_status_batch`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            packing_ids: packingIds,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`${packingIds.length} packing list diupdate ke '${status}'`);
            // Refresh data setelah update
            setTimeout(() => loadPackingList(), 1000);
        }
    })
    .catch(error => {
        console.error('Error updating batch status:', error);
    });
}

// ==================== UTILITY FUNCTIONS ====================

function refreshData() {
    currentPage = 1;
    currentSearch = '';
    document.getElementById('searchInput').value = '';
    loadPackingList();
    loadBarangList();
    showToast('Data berhasil diperbarui', 'success');
}

function generatePackingNumber() {
    const prefix = 'PL';
    const year = new Date().getFullYear();
    const month = String(new Date().getMonth() + 1).padStart(2, '0');
    
    // Generate random number for demo
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    return `${prefix}${year}${month}${random}`;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Set default packing number
    const noPackingField = document.getElementById('no_packing');
    if (noPackingField) {
        noPackingField.value = generatePackingNumber();
    }
    
    // Set default date
    const tanggalField = document.getElementById('tanggal');
    if (tanggalField) {
        tanggalField.value = new Date().toISOString().split('T')[0];
    }
});

// Export functions for global use
window.printPackingLabel = printPackingLabel;
window.printPackingList = printPackingList;
window.printSelectedLabels = printSelectedLabels;
window.refreshData = refreshData;
window.detailPacking = detailPacking;
window.editPacking = editPacking;
window.showScanActions = showScanActions;
window.konfirmasiHapus = konfirmasiHapus;
</script>
