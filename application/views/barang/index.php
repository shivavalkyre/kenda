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
                                            data-stok-min="<?php echo $barang['stok_minimum']; ?>">
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
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
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
                                                            <li>
                                                                <a class="dropdown-item text-danger hapus-btn" href="#"
                                                                   data-kode="<?php echo htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                   data-nama="<?php echo htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                    <i class="fas fa-trash me-2"></i>Hapus
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if(isset($total_pages) && $total_pages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm justify-content-end mb-0">
                                    <?php if(isset($current_page) && $current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.($current_page-1).'&search='.urlencode($search).'&filter='.$filter); ?>" tabindex="-1">
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
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page=1&search='.urlencode($search).'&filter='.$filter); ?>">1</a>
                                        </li>
                                        <?php if($start_page > 2): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.$i.'&search='.urlencode($search).'&filter='.$filter); ?>">
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
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.$total_pages.'&search='.urlencode($search).'&filter='.$filter); ?>">
                                                <?php echo $total_pages; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($current_page) && $current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo site_url('gudang/barang?page='.($current_page+1).'&search='.urlencode($search).'&filter='.$filter); ?>">
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
                <p class="text-danger"><small>Tindakan ini akan mengubah status barang menjadi nonaktif!</small></p>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Barang tidak akan benar-benar dihapus dari database, hanya statusnya yang akan diubah menjadi nonaktif.
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
const baseUrl = '<?php echo site_url(); ?>';
let currentBarangToDelete = null;

// Helper function untuk escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Helper function untuk format tanggal
function formatDate(dateTime) {
    if (!dateTime) return '-';
    try {
        const date = new Date(dateTime);
        if (isNaN(date.getTime())) return '-';
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('id-ID', options);
    } catch (e) {
        return '-';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    document.getElementById('searchButton').addEventListener('click', performSearch);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performSearch();
    });

    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Filter functionality
    document.querySelectorAll('.dropdown-item[data-filter]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            const search = document.getElementById('searchInput').value;
            window.location.href = baseUrl + '/gudang/barang?filter=' + filter + '&search=' + encodeURIComponent(search);
        });
    });

    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = baseUrl + '/gudang/export_barang';
    });

    // Delete button handler
    document.getElementById('btnHapusBarang').addEventListener('click', function() {
        if (currentBarangToDelete) {
            hapusBarang(currentBarangToDelete.kode, currentBarangToDelete.nama);
        }
    });

    // Setup event listeners untuk tombol aksi
    setupEventListeners();
    
    // Setup form handlers
    setupFormHandlers();
});

function setupEventListeners() {
    // Detail button
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const kodeBarang = this.getAttribute('data-kode');
            showDetailBarang(kodeBarang);
        });
    });

    // Edit button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const kodeBarang = this.getAttribute('data-kode');
            editBarang(kodeBarang);
        });
    });

    // Stok Awal button
    document.querySelectorAll('.stok-awal-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const kodeBarang = this.getAttribute('data-kode');
            const namaBarang = this.getAttribute('data-nama');
            const kategori = this.getAttribute('data-kategori');
            const satuan = this.getAttribute('data-satuan');
            openStokAwalModal(kodeBarang, namaBarang, kategori, satuan);
        });
    });

    // Barang Masuk button
    document.querySelectorAll('.barang-masuk-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const kodeBarang = this.getAttribute('data-kode');
            const namaBarang = this.getAttribute('data-nama');
            const kategori = this.getAttribute('data-kategori');
            const satuan = this.getAttribute('data-satuan');
            const stokSekarang = this.getAttribute('data-stok');
            openBarangMasukModal(kodeBarang, namaBarang, kategori, satuan, stokSekarang);
        });
    });

    // Barang Keluar button
    document.querySelectorAll('.barang-keluar-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const kodeBarang = this.getAttribute('data-kode');
            const namaBarang = this.getAttribute('data-nama');
            const kategori = this.getAttribute('data-kategori');
            const satuan = this.getAttribute('data-satuan');
            const stokSekarang = this.getAttribute('data-stok');
            openBarangKeluarModal(kodeBarang, namaBarang, kategori, satuan, stokSekarang);
        });
    });

    // Adjustment button
    document.querySelectorAll('.adjustment-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const kodeBarang = this.getAttribute('data-kode');
            const namaBarang = this.getAttribute('data-nama');
            const kategori = this.getAttribute('data-kategori');
            const satuan = this.getAttribute('data-satuan');
            const stokSekarang = this.getAttribute('data-stok');
            openAdjustmentModal(kodeBarang, namaBarang, kategori, satuan, stokSekarang);
        });
    });

    // Hapus button
    document.querySelectorAll('.hapus-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const kodeBarang = this.getAttribute('data-kode');
            const namaBarang = this.getAttribute('data-nama');
            konfirmasiHapus(kodeBarang, namaBarang);
        });
    });
}

function performSearch() {
    const searchTerm = document.getElementById('searchInput').value.trim();
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';
    window.location.href = baseUrl + '/gudang/barang?search=' + encodeURIComponent(searchTerm) + '&filter=' + filter;
}

function applyFilter(filter) {
    const rows = document.querySelectorAll('#barangTable tbody tr');
    
    rows.forEach(row => {
        const kategori = row.getAttribute('data-kategori');
        const stok = parseInt(row.getAttribute('data-stok') || 0);
        const stokMin = parseInt(row.getAttribute('data-stok-min') || 0);
        
        switch(filter) {
            case 'all':
                row.style.display = '';
                break;
            case 'tube':
                row.style.display = kategori === 'Tube' ? '' : 'none';
                break;
            case 'tire':
                row.style.display = kategori === 'Tire' ? '' : 'none';
                break;
            case 'stok-minimum':
                row.style.display = stok <= stokMin ? '' : 'none';
                break;
        }
    });
}

function showDetailBarang(kodeBarang) {
    fetch(baseUrl + '/gudang/api_detail_barang/' + encodeURIComponent(kodeBarang))
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderDetailModal(data.data);
            } else {
                showError('Gagal memuat detail barang: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat detail barang');
        });
}

function renderDetailModal(data) {
    // Determine colors and icons based on kategori
    let kategoriColor, kategoriIcon;
    switch(data.kategori) {
        case 'Tube':
            kategoriColor = 'primary';
            kategoriIcon = 'cog';
            break;
        case 'Tire':
            kategoriColor = 'success';
            kategoriIcon = 'tire';
            break;
        default:
            kategoriColor = 'info';
            kategoriIcon = 'box';
    }
    
    // Determine stock status
    const stok = parseInt(data.stok || 0);
    const stokMin = parseInt(data.stok_minimum || 0);
    const isLowStock = stok <= stokMin;
    const stokColor = isLowStock ? 'stock-danger' : 'stock-success';
    const stokStatus = isLowStock ? 'Perlu Restock' : 'Aman';
    
    // Format dates
    const createdDate = formatDate(data.created_at);
    const updatedDate = formatDate(data.updated_at);
    
    // Create modal content dengan card yang lebih menarik
    const detailContent = `
        <div class="detail-card">
            <div class="detail-card-header">
                <h4 class="detail-card-title">
                    <i class="fas fa-box"></i>${escapeHtml(data.nama_barang || '')}
                </h4>
                <span class="detail-card-badge">${escapeHtml(data.kode_barang || '')}</span>
            </div>
            
            <div class="detail-card-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-info-item">
                            <span class="detail-info-label">Kategori</span>
                            <span class="detail-info-value">
                                <span class="badge bg-${kategoriColor}">
                                    ${escapeHtml(data.kategori || '')}
                                </span>
                            </span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">Status</span>
                            <span class="detail-info-value">
                                <span class="badge ${data.status === 'aktif' ? 'bg-success' : 'bg-danger'}">
                                    ${data.status === 'aktif' ? 'Aktif' : 'Nonaktif'}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-info-item">
                            <span class="detail-info-label">Satuan</span>
                            <span class="detail-info-value">${escapeHtml(data.satuan || 'PCS')}</span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">Stok Minimum</span>
                            <span class="detail-info-value">${stokMin} ${escapeHtml(data.satuan || 'PCS')}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 pt-3 border-top">
                    <div class="detail-info-label mb-2">Deskripsi</div>
                    <div class="text-muted">${escapeHtml(data.deskripsi || 'Tidak ada deskripsi')}</div>
                </div>
            </div>
        </div>
        
        <div class="detail-card detail-card-primary">
            <div class="detail-card-header">
                <h5 class="detail-card-title">
                    <i class="fas fa-warehouse"></i>Kelola Stok
                </h5>
            </div>
            
            <div class="stats-card">
                <div class="stat-item">
                    <div class="stat-value ${stokColor}">${stok}</div>
                    <div class="stat-label">Stok Saat Ini</div>
                    <small class="text-muted">${escapeHtml(data.satuan || 'PCS')}</small>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${stokMin}</div>
                    <div class="stat-label">Stok Minimum</div>
                    <small class="text-muted">${escapeHtml(data.satuan || 'PCS')}</small>
                </div>
            </div>
            
            <div class="text-center">
                <div class="badge ${isLowStock ? 'bg-warning' : 'bg-success'} mb-2">
                    <i class="fas ${isLowStock ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-1"></i>
                    ${stokStatus}
                </div>
            </div>
        </div>
        
        <div class="detail-card detail-card-success">
            <div class="detail-card-header">
                <h5 class="detail-card-title">
                    <i class="fas fa-cogs"></i>Aksi Stok
                </h5>
            </div>
            
            <div class="action-grid-card">
                <div class="action-btn-card" onclick="openStokAwalModal('${escapeHtml(data.kode_barang || '')}', '${escapeHtml(data.nama_barang || '')}', '${escapeHtml(data.kategori || '')}', '${escapeHtml(data.satuan || 'PCS')}')">
                    <div class="action-icon text-primary">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="action-text">Input Stok Awal</div>
                    <div class="action-desc">Untuk barang baru tanpa stok</div>
                </div>
                
                <div class="action-btn-card" onclick="openBarangMasukModal('${escapeHtml(data.kode_barang || '')}', '${escapeHtml(data.nama_barang || '')}', '${escapeHtml(data.kategori || '')}', '${escapeHtml(data.satuan || 'PCS')}', ${stok})">
                    <div class="action-icon text-success">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="action-text">Barang Masuk</div>
                    <div class="action-desc">Tambah stok barang</div>
                </div>
                
                <div class="action-btn-card" onclick="openBarangKeluarModal('${escapeHtml(data.kode_barang || '')}', '${escapeHtml(data.nama_barang || '')}', '${escapeHtml(data.kategori || '')}', '${escapeHtml(data.satuan || 'PCS')}', ${stok})">
                    <div class="action-icon text-warning">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="action-text">Barang Keluar</div>
                    <div class="action-desc">Kurangi stok barang</div>
                </div>
                
                <div class="action-btn-card" onclick="openAdjustmentModal('${escapeHtml(data.kode_barang || '')}', '${escapeHtml(data.nama_barang || '')}', '${escapeHtml(data.kategori || '')}', '${escapeHtml(data.satuan || 'PCS')}', ${stok})">
                    <div class="action-icon text-info">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="action-text">Adjustment Stok</div>
                    <div class="action-desc">Koreksi stok</div>
                </div>
            </div>
        </div>
        
        <div class="timestamp-card">
            <div class="row">
                <div class="col-md-6">
                    <div class="timestamp-item">
                        <div class="timestamp-label">Dibuat</div>
                        <div class="timestamp-value">${createdDate}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="timestamp-item">
                        <div class="timestamp-label">Diupdate</div>
                        <div class="timestamp-value">${updatedDate}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-2"></i>Tutup
            </button>
            <button type="button" class="btn btn-kenda" onclick="editBarang('${escapeHtml(data.kode_barang || '')}')" data-bs-dismiss="modal">
                <i class="fas fa-edit me-2"></i>Edit Barang
            </button>
        </div>
    `;
    
    document.getElementById('detailBarangContent').innerHTML = detailContent;
    document.getElementById('detailBarangModalLabel').innerHTML = `<i class="fas fa-eye me-2"></i>Detail - ${escapeHtml(data.nama_barang || '')}`;
    
    const detailModal = new bootstrap.Modal(document.getElementById('detailBarangModal'));
    detailModal.show();
}

function openStokAwalModal(kodeBarang, namaBarang, kategori, satuan) {
    document.getElementById('stok_awal_kode_barang_hidden').value = kodeBarang;
    document.getElementById('stok_awal_kode_barang_display').value = kodeBarang;
    document.getElementById('stok_awal_nama_barang').value = namaBarang;
    document.getElementById('stok_awal_kategori').value = kategori;
    document.getElementById('stok_awal_satuan').value = satuan;
    
    document.getElementById('stok_awal_jumlah').value = '';
    document.getElementById('stok_awal_keterangan').value = 'Stok awal barang';
    document.getElementById('stok_awal_tanggal').value = new Date().toISOString().split('T')[0];
    
    const stokAwalModal = new bootstrap.Modal(document.getElementById('stokAwalModal'));
    stokAwalModal.show();
}

function openBarangMasukModal(kodeBarang, namaBarang, kategori, satuan, stokSekarang) {
    document.getElementById('barang_masuk_kode_barang').value = kodeBarang;
    document.getElementById('barang_masuk_kode_barang_display').value = kodeBarang;
    document.getElementById('barang_masuk_nama_barang').value = namaBarang;
    document.getElementById('barang_masuk_kategori').value = kategori;
    document.getElementById('barang_masuk_satuan').value = satuan;
    document.getElementById('barang_masuk_stok_sekarang').value = stokSekarang;
    
    document.getElementById('barang_masuk_jumlah').value = '';
    document.getElementById('barang_masuk_supplier').value = '';
    document.getElementById('barang_masuk_no_po').value = '';
    document.getElementById('barang_masuk_keterangan').value = '';
    document.getElementById('barang_masuk_tanggal').value = new Date().toISOString().split('T')[0];
    
    const barangMasukModal = new bootstrap.Modal(document.getElementById('barangMasukModal'));
    barangMasukModal.show();
}

function openBarangKeluarModal(kodeBarang, namaBarang, kategori, satuan, stokSekarang) {
    document.getElementById('barang_keluar_kode_barang').value = kodeBarang;
    document.getElementById('barang_keluar_kode_barang_display').value = kodeBarang;
    document.getElementById('barang_keluar_nama_barang').value = namaBarang;
    document.getElementById('barang_keluar_kategori').value = kategori;
    document.getElementById('barang_keluar_satuan').value = satuan;
    document.getElementById('barang_keluar_stok_sekarang').value = stokSekarang;
    
    document.getElementById('barang_keluar_jumlah').value = '';
    document.getElementById('barang_keluar_customer').value = '';
    document.getElementById('barang_keluar_no_sj').value = '';
    document.getElementById('barang_keluar_keterangan').value = '';
    document.getElementById('barang_keluar_tanggal').value = new Date().toISOString().split('T')[0];
    
    const barangKeluarModal = new bootstrap.Modal(document.getElementById('barangKeluarModal'));
    barangKeluarModal.show();
}

function openAdjustmentModal(kodeBarang, namaBarang, kategori, satuan, stokSekarang) {
    document.getElementById('adjustment_kode_barang').value = kodeBarang;
    document.getElementById('adjustment_kode_barang_display').value = kodeBarang;
    document.getElementById('adjustment_nama_barang').value = namaBarang;
    document.getElementById('adjustment_kategori').value = kategori;
    document.getElementById('adjustment_satuan').value = satuan;
    document.getElementById('adjustment_stok_sekarang').value = stokSekarang;
    document.getElementById('adjustment_stok_baru').value = stokSekarang;
    
    document.getElementById('adjustment_alasan').value = '';
    document.getElementById('adjustment_keterangan_lainnya').value = '';
    
    const adjustmentModal = new bootstrap.Modal(document.getElementById('adjustmentModal'));
    adjustmentModal.show();
}

function editBarang(kodeBarang) {
    fetch(baseUrl + '/gudang/api_detail_barang/' + encodeURIComponent(kodeBarang))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit_kode_barang').value = data.data.kode_barang;
                document.getElementById('edit_kode_display').value = data.data.kode_barang;
                document.getElementById('edit_nama_barang').value = data.data.nama_barang;
                document.getElementById('edit_kategori').value = data.data.kategori;
                document.getElementById('edit_satuan').value = data.data.satuan || 'PCS';
                document.getElementById('edit_stok_minimum').value = data.data.stok_minimum || 0;
                document.getElementById('edit_status').value = data.data.status || 'aktif';
                document.getElementById('edit_deskripsi').value = data.data.deskripsi || '';
                
                const editModal = new bootstrap.Modal(document.getElementById('editBarangModal'));
                editModal.show();
            } else {
                showError('Gagal memuat data barang: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data barang');
        });
}

function konfirmasiHapus(kodeBarang, nama) {
    currentBarangToDelete = { kode: kodeBarang, nama: nama };
    document.getElementById('namaBarangHapus').textContent = nama;
    
    const konfirmasiModal = new bootstrap.Modal(document.getElementById('konfirmasiHapusModal'));
    konfirmasiModal.show();
}

function hapusBarang(kodeBarang, nama) {
    fetch(baseUrl + '/gudang/hapus_barang/' + encodeURIComponent(kodeBarang), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            const konfirmasiModal = bootstrap.Modal.getInstance(document.getElementById('konfirmasiHapusModal'));
            konfirmasiModal.hide();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menghapus barang');
    });
}

function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            timer: 3000,
            confirmButtonColor: '#0056b3'
        });
    } else {
        alert('Error: ' + message);
    }
}

function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: message,
            timer: 2000,
            confirmButtonColor: '#0056b3'
        });
    } else {
        alert('Success: ' + message);
    }
}

function showLoading(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Menyimpan...',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
}

function hideLoading() {
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
}

function setupFormHandlers() {
    // Tambah Barang Form
    const formTambahBarang = document.getElementById('formTambahBarang');
    if (formTambahBarang) {
        formTambahBarang.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const kodeBarang = document.getElementById('kode_barang').value.trim();
            if (!kodeBarang) {
                showError('Kode barang harus diisi');
                return;
            }
            
            const formData = new FormData(this);
            
            showLoading('Menyimpan data barang...');
            
            fetch(baseUrl + '/gudang/tambah_barang', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('tambahBarangModal'));
                    modal.hide();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan barang');
            });
        });
    }

    // Stok Awal Form
    const formStokAwal = document.getElementById('formStokAwal');
    if (formStokAwal) {
        formStokAwal.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jumlah = document.getElementById('stok_awal_jumlah').value;
            if (!jumlah || parseInt(jumlah) < 0) {
                showError('Jumlah stok awal harus diisi dan minimal 0');
                return;
            }
            
            const formData = new FormData(this);
            
            showLoading('Menyimpan stok awal...');
            
            fetch(baseUrl + '/gudang/stok_awal', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('stokAwalModal'));
                    modal.hide();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan stok awal');
            });
        });
    }

    // Edit Barang Form
    const formEditBarang = document.getElementById('formEditBarang');
    if (formEditBarang) {
        formEditBarang.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const namaBarang = document.getElementById('edit_nama_barang').value.trim();
            if (!namaBarang) {
                showError('Nama barang harus diisi');
                return;
            }
            
            const formData = new FormData(this);
            
            showLoading('Mengupdate data barang...');
            
            fetch(baseUrl + '/gudang/update_barang', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editBarangModal'));
                    modal.hide();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat mengupdate barang');
            });
        });
    }
    
    // Barang Masuk Form
    const formBarangMasuk = document.getElementById('formBarangMasuk');
    if (formBarangMasuk) {
        formBarangMasuk.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jumlah = document.getElementById('barang_masuk_jumlah').value;
            if (!jumlah || parseInt(jumlah) <= 0) {
                showError('Jumlah barang masuk harus diisi dan minimal 1');
                return;
            }
            
            const formData = new FormData(this);
            
            showLoading('Menyimpan barang masuk...');
            
            fetch(baseUrl + '/gudang/barang_masuk', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('barangMasukModal'));
                    modal.hide();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan barang masuk');
            });
        });
    }
    
    // Barang Keluar Form
    const formBarangKeluar = document.getElementById('formBarangKeluar');
    if (formBarangKeluar) {
        formBarangKeluar.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jumlah = document.getElementById('barang_keluar_jumlah').value;
            const stokSekarang = parseInt(document.getElementById('barang_keluar_stok_sekarang').value) || 0;
            
            if (!jumlah || parseInt(jumlah) <= 0) {
                showError('Jumlah barang keluar harus diisi dan minimal 1');
                return;
            }
            
            if (parseInt(jumlah) > stokSekarang) {
                showError(`Jumlah keluar (${jumlah}) melebihi stok tersedia (${stokSekarang})`);
                return;
            }
            
            const formData = new FormData(this);
            
            showLoading('Menyimpan barang keluar...');
            
            fetch(baseUrl + '/gudang/barang_keluar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('barangKeluarModal'));
                    modal.hide();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan barang keluar');
            });
        });
    }
    
    // Adjustment Form
    const formAdjustment = document.getElementById('formAdjustment');
    if (formAdjustment) {
        formAdjustment.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const stokBaru = document.getElementById('adjustment_stok_baru').value;
            const alasan = document.getElementById('adjustment_alasan').value;
            
            if (!stokBaru || parseInt(stokBaru) < 0) {
                showError('Stok baru harus diisi dan minimal 0');
                return;
            }
            
            if (!alasan) {
                showError('Alasan adjustment harus dipilih');
                return;
            }
            
            const formData = new FormData(this);
            
            showLoading('Menyimpan adjustment stok...');
            
            fetch(baseUrl + '/gudang/adjustment_stok', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('adjustmentModal'));
                    modal.hide();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan adjustment');
            });
        });
    }
}

// Initialize on page load
window.onload = function() {
    // Check for URL parameters and apply filters
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');
    const search = urlParams.get('search');
    
    if (filter) {
        applyFilter(filter);
    }
    
    if (search) {
        document.getElementById('searchInput').value = decodeURIComponent(search);
    }
    
    // Show success message if redirected from form submission
    const success = urlParams.get('success');
    if (success === 'true') {
        showSuccess('Operasi berhasil dilakukan');
        // Remove the parameter from URL
        const newUrl = window.location.pathname + window.location.search.replace(/[?&]success=true/, '');
        window.history.replaceState({}, document.title, newUrl);
    }
};
</script>
