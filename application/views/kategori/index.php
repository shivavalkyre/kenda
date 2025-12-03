<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="container-fluid">
    <!-- Flash Message -->
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $this->session->flashdata('warning'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tags me-2"></i>Kategori Barang
                </h1>
                <p class="text-muted">Kelola kategori barang untuk pengelompokan produk</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-primary" id="totalKategori">0</div>
                <div class="stat-label">Total Kategori</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-tags me-1"></i> Semua kategori
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-success" id="activeKategori">0</div>
                <div class="stat-label">Kategori Aktif</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check-circle me-1"></i> Tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-warning" id="inactiveKategori">0</div>
                <div class="stat-label">Kategori Nonaktif</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-pause-circle me-1"></i> Ditangguhkan
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-info" id="totalBarang">0</div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-trend">
                    <i class="fas fa-boxes me-1"></i> Semua kategori
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                    <i class="fas fa-plus me-2"></i>Tambah Kategori
                </button>
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>Filter Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-status="all">Semua</a></li>
                        <li><a class="dropdown-item" href="#" data-status="active">Aktif</a></li>
                        <li><a class="dropdown-item" href="#" data-status="inactive">Nonaktif</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Kategori Barang
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="kategoriTable">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Kode Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Barang</th>
                                    <th>Status</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="kategoriTableBody">
                                <!-- Data akan di-load via AJAX -->
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-kenda-red" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Memuat data kategori...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="card-footer pagination-container" id="paginationContainer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="pagination-info" id="paginationInfo">
                                Menampilkan 0 dari 0 data
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="pagination-nav">
                                <ul class="pagination pagination-sm mb-0" id="paginationNav">
                                    <!-- Pagination akan di-load via AJAX -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="tambahKategoriModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="tambahKategoriModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahKategori">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_kategori" class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_kategori" name="kode_kategori" required 
                                       placeholder="Contoh: TUB, TIR, ACC" maxlength="10">
                                <div class="form-text">Kode unik untuk kategori (maksimal 10 karakter)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required 
                                       placeholder="Contoh: Tube, Tire, Accessories">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                          placeholder="Deskripsi kategori..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-kenda">
                        <i class="fas fa-save me-2"></i>Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="editKategoriModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Kategori
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKategori">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_kode_kategori" class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_kode_kategori" name="kode_kategori" required 
                                       placeholder="Contoh: TUB, TIR, ACC" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required 
                                       placeholder="Contoh: Tube, Tire, Accessories">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" 
                                          placeholder="Deskripsi kategori..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-kenda">
                        <i class="fas fa-save me-2"></i>Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Kategori - DIUBAH MENJADI SEPERTI BARANG -->
<div class="modal fade" id="detailKategoriModal" tabindex="-1" aria-labelledby="detailKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="detailKategoriModalLabel">
                    <i class="fas fa-eye me-2"></i>Detail Kategori
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="detailKategoriContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail kategori...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* STYLE UTAMA TETAP TIDAK DIUBAH */
.info-group {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 0.75rem;
}

.info-group:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.section-title {
    color: #495057;
    font-weight: 600;
    font-size: 1.1rem;
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
    transition: all 0.3s;
}

.btn-kenda:hover {
    background-color: #004494;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-kenda-red {
    background-color: #dc3545;
    color: white;
    border: none;
    transition: all 0.3s;
}

.btn-kenda-red:hover {
    background-color: #c82333;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
}

.text-kenda {
    color: #0056b3 !important;
}

.text-kenda-red {
    color: #dc3545 !important;
}

.spinner-border.text-kenda {
    color: #0056b3 !important;
}

/* Alert Styles */
.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #d1f7dc;
    color: #0f5132;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

/* STYLE CARD SEPERTI BARANG - TAMBAHAN BARU */
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

/* Stats card untuk jumlah barang */
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

.stat-value.count-primary {
    color: #0056b3;
}

.stat-value.count-success {
    color: #28a745;
}

.stat-value.count-warning {
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

/* Table untuk barang di kategori */
.barang-table {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.barang-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: #495057;
    padding: 0.75rem;
}

.barang-table td {
    padding: 0.75rem;
    border-top: 1px solid #e9ecef;
}

.barang-table tr:hover {
    background-color: #f8f9fa;
}

.barang-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Khusus untuk modal detail kategori */
#detailKategoriContent {
    max-height: 70vh;
    overflow-y: auto;
    padding: 1.5rem;
}

#detailKategoriContent::-webkit-scrollbar {
    width: 6px;
}

#detailKategoriContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#detailKategoriContent::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

/* Pagination Styles */
.pagination-container {
    background: #fff;
    border-top: 1px solid #dee2e6;
    padding: 15px 20px;
    border-radius: 0 0 8px 8px;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    height: 100%;
}

.pagination-nav {
    display: flex;
    justify-content: flex-end;
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-item {
    margin: 0 2px;
}

.pagination .page-link {
    color: #495057;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    min-width: 36px;
    text-align: center;
    transition: all 0.2s;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #0056b3;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background-color: #0056b3;
    border-color: #0056b3;
    color: white;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    color: #adb5bd;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
    outline: none;
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
    
    .pagination-container .row {
        flex-direction: column;
        gap: 10px;
    }
    
    .pagination-info {
        justify-content: center;
        text-align: center;
    }
    
    .pagination-nav {
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        min-width: 32px;
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

<!-- SweetAlert2 JS - MASIH ADA TAPI TIDAK DIGUNAKAN UNTUK LOADING/KONFIRMASI -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const baseUrl = '<?php echo site_url(); ?>';
let currentPage = 1;
let totalPages = 1;
let limit = 10; // Items per page
let totalItems = 0;
let currentStatusFilter = 'all';

// Hanya fungsi escapeHtml yang masih digunakan
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

document.addEventListener('DOMContentLoaded', function() {
    loadKategoriList(currentPage);
    loadStatistics();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter functionality
    document.querySelectorAll('.dropdown-item[data-status]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            currentStatusFilter = this.getAttribute('data-status');
            currentPage = 1; // Reset to first page when filtering
            loadKategoriList(currentPage, currentStatusFilter);
            applyFilter(currentStatusFilter);
        });
    });

    // Form submission
    document.getElementById('formTambahKategori').addEventListener('submit', function(e) {
        e.preventDefault();
        simpanKategori();
    });

    document.getElementById('formEditKategori').addEventListener('submit', function(e) {
        e.preventDefault();
        updateKategori();
    });

    // Reset form when modal is closed
    document.getElementById('tambahKategoriModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('formTambahKategori').reset();
    });
});

function loadKategoriList(page = 1, status = 'all') {
    fetch(`${baseUrl}/gudang/api_list_kategori?page=${page}&limit=${limit}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Pastikan data ada dan valid
                totalItems = parseInt(data.total_items) || 0;
                totalPages = parseInt(data.total_pages) || 1;
                currentPage = parseInt(data.current_page) || 1;
                
                // Pastikan data.data adalah array
                const kategoriList = Array.isArray(data.data) ? data.data : [];
                
                renderKategoriTable(kategoriList);
                renderPagination();
            } else {
                // Tampilkan error di console saja
                console.error('Gagal memuat data kategori:', data.message || '');
                // Reset data jika gagal
                totalItems = 0;
                renderKategoriTable([]);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Reset data jika error
            totalItems = 0;
            renderKategoriTable([]);
        });
}

function loadStatistics() {
    fetch(`${baseUrl}/gudang/api_kategori_statistics`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalKategori').textContent = data.data.total_kategori;
                document.getElementById('activeKategori').textContent = data.data.active_kategori;
                document.getElementById('inactiveKategori').textContent = data.data.inactive_kategori;
                document.getElementById('totalBarang').textContent = data.data.total_barang;
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
}

function renderKategoriTable(kategoriList) {
    const tbody = document.getElementById('kategoriTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    
    // Pastikan kategoriList ada dan array
    if (!kategoriList || !Array.isArray(kategoriList) || kategoriList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-tags fa-3x mb-3"></i>
                        <p>Tidak ada data kategori</p>
                        <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                        </button>
                    </div>
                </td>
            </tr>
        `;
        
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        
        // Update totalItems ke 0
        totalItems = 0;
        updatePaginationInfo();
        return;
    }
    
    // Tampilkan pagination jika ada data
    if (paginationContainer) {
        paginationContainer.style.display = 'flex';
    }
    
    // Update totalItems
    totalItems = totalItems || kategoriList.length;
    
    let html = '';
    const startNumber = (currentPage - 1) * limit + 1;
    
    kategoriList.forEach((kategori, index) => {
        // Escape karakter khusus untuk menghindari XSS
        const namaKategori = escapeHtml(kategori.nama_kategori || '');
        const kodeKategori = escapeHtml(kategori.kode_kategori || '');
        const deskripsi = escapeHtml(kategori.deskripsi || '') || '-';
        const jumlahBarang = kategori.jumlah_barang || 0;
        
        const statusBadge = kategori.status === 'active' ? 
            '<span class="badge bg-success">Aktif</span>' : 
            '<span class="badge bg-secondary">Nonaktif</span>';
        
        html += `
            <tr data-status="${kategori.status}">
                <td>${startNumber + index}</td>
                <td>
                    <span class="badge bg-dark">${kodeKategori}</span>
                </td>
                <td class="fw-bold">${namaKategori}</td>
                <td>${deskripsi}</td>
                <td>
                    <span class="fw-bold text-primary">${jumlahBarang}</span> barang
                </td>
                <td>${statusBadge}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary" 
                                onclick="detailKategori(${kategori.id})"
                                data-bs-toggle="tooltip" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success"
                                onclick="editKategori(${kategori.id})"
                                data-bs-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger"
                                onclick="hapusKategori(${kategori.id}, '${namaKategori.replace(/'/g, "\\'")}')"
                                data-bs-toggle="tooltip" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    
    // Update pagination info
    updatePaginationInfo();
    
    // Re-initialize tooltips for new elements
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function renderPagination() {
    const paginationNav = document.getElementById('paginationNav');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (!paginationNav || !paginationContainer) return;
    
    const ul = paginationNav;
    
    // Jika tidak ada data atau hanya 1 halaman, sembunyikan pagination
    if (totalItems <= 0 || totalPages <= 1) {
        ul.innerHTML = '';
        paginationContainer.style.display = 'none';
        return;
    }
    
    // Tampilkan pagination container
    paginationContainer.style.display = 'block';
    
    let html = '';
    const maxVisiblePages = 5;
    
    // Previous button
    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${currentPage - 1}); return false;" 
               aria-label="Previous" ${currentPage === 1 ? 'tabindex="-1" aria-disabled="true"' : ''}>
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Calculate visible page range
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Adjust if we're at the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // First page and ellipsis
    if (startPage > 1) {
        html += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(1); return false;">1</a>
            </li>
        `;
        if (startPage > 2) {
            html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            html += `
                <li class="page-item active" aria-current="page">
                    <span class="page-link">${i}</span>
                </li>
            `;
        } else {
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
                </li>
            `;
        }
    }
    
    // Last page and ellipsis
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }
        html += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(${totalPages}); return false;">${totalPages}</a>
            </li>
        `;
    }
    
    // Next button
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${currentPage + 1}); return false;" 
               aria-label="Next" ${currentPage === totalPages ? 'tabindex="-1" aria-disabled="true"' : ''}>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
    
    ul.innerHTML = html;
    
    // Update pagination info
    updatePaginationInfo();
}

function updatePaginationInfo() {
    const startItem = totalItems > 0 ? (currentPage - 1) * limit + 1 : 0;
    const endItem = totalItems > 0 ? Math.min(currentPage * limit, totalItems) : 0;
    
    let infoText = '';
    if (totalItems > 0) {
        infoText = `Menampilkan ${startItem}-${endItem} dari ${totalItems} data`;
        if (totalPages > 1) {
            infoText += ` (Halaman ${currentPage} dari ${totalPages})`;
        }
    } else {
        infoText = 'Tidak ada data yang ditemukan';
    }
    
    const paginationInfo = document.getElementById('paginationInfo');
    if (paginationInfo) {
        paginationInfo.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <span>${infoText}</span>
            </div>
        `;
    }
}

function goToPage(page) {
    if (page < 1 || page > totalPages || page === currentPage) {
        return false;
    }
    
    currentPage = page;
    loadKategoriList(currentPage, currentStatusFilter);
    
    // Smooth scroll to top of table
    const table = document.querySelector('.card');
    if (table) {
        table.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
    
    // Highlight active page button
    setTimeout(() => {
        highlightActivePage();
    }, 500);
    
    return false;
}

function highlightActivePage() {
    const pageLinks = document.querySelectorAll('.pagination .page-link');
    pageLinks.forEach(link => {
        link.classList.remove('active');
        if (parseInt(link.textContent) === currentPage) {
            link.classList.add('active');
        }
    });
}

function simpanKategori() {
    const formData = new FormData(document.getElementById('formTambahKategori'));
    const submitBtn = document.querySelector('#formTambahKategori button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    
    fetch(`${baseUrl}/kategori/simpan`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            // Close modal and refresh data
            const modal = bootstrap.Modal.getInstance(document.getElementById('tambahKategoriModal'));
            modal.hide();
            
            // Reload page untuk menampilkan flash message
            window.location.reload();
        } else {
            // Tampilkan error di console
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        console.error('Error:', error);
    });
}

function editKategori(id) {
    fetch(`${baseUrl}/gudang/api_detail_kategori/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            } else {
                console.error('Gagal memuat data kategori');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function showEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_kode_kategori').value = data.kode_kategori;
    document.getElementById('edit_nama_kategori').value = data.nama_kategori;
    document.getElementById('edit_deskripsi').value = data.deskripsi || '';
    document.getElementById('edit_status').value = data.status;
    
    document.getElementById('editKategoriModalLabel').innerHTML = `<i class="fas fa-edit me-2"></i>Edit - ${data.nama_kategori}`;
    
    const modal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
    modal.show();
}

function updateKategori() {
    const formData = new FormData(document.getElementById('formEditKategori'));
    const submitBtn = document.querySelector('#formEditKategori button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
    
    fetch(`${baseUrl}/gudang/update_kategori`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            // Close modal and refresh data
            const modal = bootstrap.Modal.getInstance(document.getElementById('editKategoriModal'));
            modal.hide();
            
            // Reload page untuk menampilkan flash message
            window.location.reload();
        } else {
            // Tampilkan error di console
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        console.error('Error:', error);
    });
}

// FUNGSI DETAIL KATEGORI YANG SUDAH DIUBAH SEPERTI BARANG
function detailKategori(id) {
    fetch(`${baseUrl}/gudang/api_detail_kategori/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDetailModal(data.data);
            } else {
                console.error('Gagal memuat detail kategori');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function showDetailModal(data) {
    // Determine colors and icons based on status
    let statusColor, statusIcon, statusText;
    if (data.status === 'active') {
        statusColor = 'success';
        statusIcon = 'check-circle';
        statusText = 'Aktif';
    } else {
        statusColor = 'secondary';
        statusIcon = 'pause-circle';
        statusText = 'Nonaktif';
    }
    
    // Format dates
    const createdDate = formatTanggal(data.created_at);
    const updatedDate = formatTanggal(data.updated_at);
    
    // Get barang count
    const jumlahBarang = data.jumlah_barang || 0;
    
    // Create modal content dengan card yang lebih menarik seperti barang
    const detailContent = `
        <div class="detail-card">
            <div class="detail-card-header">
                <h4 class="detail-card-title">
                    <i class="fas fa-tag"></i>${escapeHtml(data.nama_kategori || '')}
                </h4>
                <span class="detail-card-badge">${escapeHtml(data.kode_kategori || '')}</span>
            </div>
            
            <div class="detail-card-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-info-item">
                            <span class="detail-info-label">Kode Kategori</span>
                            <span class="detail-info-value">
                                <span class="badge bg-dark">${escapeHtml(data.kode_kategori || '')}</span>
                            </span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">Status</span>
                            <span class="detail-info-value">
                                <span class="badge bg-${statusColor}">
                                    <i class="fas fa-${statusIcon} me-1"></i>${statusText}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-info-item">
                            <span class="detail-info-label">Jumlah Barang</span>
                            <span class="detail-info-value">
                                <span class="badge bg-primary">${jumlahBarang} barang</span>
                            </span>
                        </div>
                        <div class="detail-info-item">
                            <span class="detail-info-label">ID Kategori</span>
                            <span class="detail-info-value">#${data.id || ''}</span>
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
                    <i class="fas fa-chart-pie"></i>Statistik Barang
                </h5>
            </div>
            
            <div class="stats-card">
                <div class="stat-item">
                    <div class="stat-value ${jumlahBarang > 0 ? 'count-primary' : 'count-warning'}">${jumlahBarang}</div>
                    <div class="stat-label">Total Barang</div>
                    <small class="text-muted">Dalam kategori ini</small>
                </div>
                <div class="stat-item">
                    <div class="stat-value count-success">${data.status === 'active' ? 'Aktif' : 'Tidak'}</div>
                    <div class="stat-label">Status Kategori</div>
                    <small class="text-muted">Untuk penggunaan</small>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <div class="badge ${jumlahBarang > 0 ? 'bg-success' : 'bg-warning'}">
                    <i class="fas ${jumlahBarang > 0 ? 'fa-boxes' : 'fa-box-open'} me-1"></i>
                    ${jumlahBarang > 0 ? 'Memiliki barang' : 'Belum ada barang'}
                </div>
            </div>
        </div>
        
        ${data.barang_terbaru && data.barang_terbaru.length > 0 ? `
        <div class="detail-card detail-card-info">
            <div class="detail-card-header">
                <h5 class="detail-card-title">
                    <i class="fas fa-boxes"></i>Barang Terbaru
                    <span class="badge bg-primary ms-2">${data.barang_terbaru.length}</span>
                </h5>
            </div>
            
            <div class="table-responsive mt-3">
                <table class="table table-sm barang-table">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.barang_terbaru.map(barang => `
                            <tr>
                                <td>
                                    <span class="badge bg-dark barang-badge">${barang.kode_barang || '-'}</span>
                                </td>
                                <td class="fw-semibold">${escapeHtml(barang.nama_barang || '-')}</td>
                                <td>
                                    <span class="badge ${parseInt(barang.stok || 0) <= parseInt(barang.stok_minimum || 0) ? 'bg-warning' : 'bg-success'} barang-badge">
                                        ${barang.stok || 0}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge ${barang.status === 'aktif' ? 'bg-success' : 'bg-danger'} barang-badge">
                                        ${barang.status === 'aktif' ? 'Aktif' : 'Nonaktif'}
                                    </span>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
        ` : ''}
        
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
            <button type="button" class="btn btn-kenda" onclick="editKategori(${data.id})" data-bs-dismiss="modal">
                <i class="fas fa-edit me-2"></i>Edit Kategori
            </button>
        </div>
    `;
    
    document.getElementById('detailKategoriContent').innerHTML = detailContent;
    document.getElementById('detailKategoriModalLabel').innerHTML = `<i class="fas fa-eye me-2"></i>Detail - ${escapeHtml(data.nama_kategori || '')}`;
    
    const modal = new bootstrap.Modal(document.getElementById('detailKategoriModal'));
    modal.show();
}

function hapusKategori(id, nama) {
    // Menggunakan konfirmasi browser default
    if (confirm(`Apakah Anda yakin ingin menghapus kategori "${nama}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        fetch(`${baseUrl}/gudang/hapus_kategori/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page untuk menampilkan flash message
                    window.location.reload();
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

function applyFilter(status) {
    const rows = document.querySelectorAll('#kategoriTable tbody tr');
    
    rows.forEach(function(row) {
        const rowStatus = row.getAttribute('data-status');
        
        switch(status) {
            case 'all':
                row.style.display = '';
                break;
            case 'active':
                row.style.display = rowStatus === 'active' ? '' : 'none';
                break;
            case 'inactive':
                row.style.display = rowStatus === 'inactive' ? '' : 'none';
                break;
        }
    });
}

function refreshData() {
    loadKategoriList(currentPage, currentStatusFilter);
    loadStatistics();
}

// Helper function untuk format tanggal
function formatTanggal(tanggal) {
    if (!tanggal) return '-';
    
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    
    try {
        return new Date(tanggal).toLocaleDateString('id-ID', options);
    } catch (e) {
        return tanggal;
    }
}
</script>