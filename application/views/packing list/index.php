<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-clipboard-list me-2"></i>Packing List
                </h1>
                <p class="text-muted">Buat dan kelola packing list untuk barang keluar</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-primary"><?php echo $total_packing ?? '15'; ?></div>
                <div class="stat-label">Total Packing</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up me-1"></i> Bulan ini
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-warning"><?php echo $pending_packing ?? '3'; ?></div>
                <div class="stat-label">Pending</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-clock me-1"></i> Perlu diproses
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-success"><?php echo $completed_packing ?? '12'; ?></div>
                <div class="stat-label">Selesai</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check me-1"></i> Terproses
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-info"><?php echo $total_items ?? '485'; ?></div>
                <div class="stat-label">Total Item</div>
                <div class="stat-trend">
                    <i class="fas fa-box me-1"></i> Dipacking
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-between">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#buatPackingModal">
                        <i class="fas fa-plus me-2"></i>Buat Packing List
                    </button>
                    <button class="btn btn-kenda-red" id="printLabelsBtn">
                        <i class="fas fa-print me-2"></i>Cetak Label
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-2"></i>Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-status="all">Semua</a></li>
                            <li><a class="dropdown-item" href="#" data-status="draft">Draft</a></li>
                            <li><a class="dropdown-item" href="#" data-status="printed">Label Tercetak</a></li>
                            <li><a class="dropdown-item" href="#" data-status="scanned">Terkirim</a></li>
                            <li><a class="dropdown-item" href="#" data-status="loaded">Loading Selesai</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-outline-info" onclick="refreshData()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Cari packing list..." id="searchInput">
                        <button class="btn btn-kenda" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Packing List Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Packing List
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="packingTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAllPacking">
                                    </th>
                                    <th width="60">No</th>
                                    <th>No. Packing</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Jumlah Item</th>
                                    <th>Status Label</th>
                                    <th>Status Loading</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="packingTableBody">
                                <!-- Data akan di-load via AJAX -->
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="spinner-border text-kenda-red" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Memuat data packing list...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="card-footer bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="text-muted" id="paginationInfo">
                                Menampilkan data...
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm justify-content-end mb-0" id="paginationContainer">
                                    <!-- Pagination akan di-generate via JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Packing List -->
<div class="modal fade" id="buatPackingModal" tabindex="-1" aria-labelledby="buatPackingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="buatPackingModalLabel">
                    <i class="fas fa-plus me-2"></i>Buat Packing List Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBuatPacking">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_packing" class="form-label">No. Packing List <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_packing" name="no_packing" required 
                                       value="PL<?php echo sprintf('%03d', (($total_packing ?? 0) + 1)); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required 
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer" name="customer" required 
                                       placeholder="Nama customer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2" 
                                          placeholder="Alamat pengiriman..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="section-header mb-3">
                                <h6 class="section-title mb-0">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Item Barang
                                </h6>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <select class="form-select" id="pilihBarang">
                                        <option value="">Pilih Barang...</option>
                                        <!-- Options akan di-load via AJAX -->
                                    </select>
                                    <input type="number" class="form-control" id="qtyBarang" placeholder="Qty" min="1" value="1" style="max-width: 100px;">
                                    <button type="button" class="btn btn-kenda" onclick="tambahItem()">
                                        <i class="fas fa-plus me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="section-header mb-3">
                                <h6 class="section-title mb-0">
                                    <i class="fas fa-list me-2"></i>Daftar Item
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tabelItem">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th width="100">Kategori</th>
                                            <th width="120" class="text-end">Quantity</th>
                                            <th width="80" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyItem">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total:</td>
                                            <td id="totalQty" class="text-end fw-bold text-primary">0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2" 
                                          placeholder="Keterangan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-kenda">
                        <i class="fas fa-save me-2"></i>Simpan Packing List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Packing List -->
<div class="modal fade" id="editPackingModal" tabindex="-1" aria-labelledby="editPackingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="editPackingModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Packing List
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditPacking">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_no_packing" class="form-label">No. Packing List <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_no_packing" name="no_packing" required readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_customer" name="customer" required 
                                       placeholder="Nama customer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_alamat" class="form-label">Alamat Pengiriman</label>
                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="2" 
                                          placeholder="Alamat pengiriman..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="section-header mb-3">
                                <h6 class="section-title mb-0">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Item Barang
                                </h6>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <select class="form-select" id="edit_pilihBarang">
                                        <option value="">Pilih Barang...</option>
                                        <!-- Options akan di-load via AJAX -->
                                    </select>
                                    <input type="number" class="form-control" id="edit_qtyBarang" placeholder="Qty" min="1" value="1" style="max-width: 100px;">
                                    <button type="button" class="btn btn-kenda" onclick="tambahItemEdit()">
                                        <i class="fas fa-plus me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="section-header mb-3">
                                <h6 class="section-title mb-0">
                                    <i class="fas fa-list me-2"></i>Daftar Item
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="edit_tabelItem">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th width="100">Kategori</th>
                                            <th width="120" class="text-end">Quantity</th>
                                            <th width="80" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="edit_bodyItem">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total:</td>
                                            <td id="edit_totalQty" class="text-end fw-bold text-primary">0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="edit_keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="2" 
                                          placeholder="Keterangan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-kenda">
                        <i class="fas fa-save me-2"></i>Update Packing List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Packing List -->
<div class="modal fade" id="detailPackingModal" tabindex="-1" aria-labelledby="detailPackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="detailPackingModalLabel">
                    <i class="fas fa-eye me-2"></i>Detail Packing List
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="detailPackingContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-kenda-red" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat detail packing list...</p>
                </div>
            </div>
            <div class="modal-footer bg-light py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <button type="button" class="btn btn-kenda" onclick="printLabelFromDetail()">
                    <i class="fas fa-print me-2"></i>Cetak Label
                </button>
            </div>
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
                <p>Apakah Anda yakin ingin menghapus packing list <strong id="namaPackingHapus"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan semua data item akan ikut terhapus!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnHapusPacking">
                    <i class="fas fa-trash me-2"></i>Hapus Packing List
                </button>
            </div>
        </div>
    </div>
</div>

<style>
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

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bg-tube {
    background-color: #007bff !important;
}

.bg-tire {
    background-color: #28a745 !important;
}

.alert-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
}

.page-item.active .page-link {
    background-color: #dc3545;
    border-color: #dc3545;
}

.page-link {
    color: #dc3545;
}

.page-link:hover {
    color: #a71e2a;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>

<script>
const baseUrl = '<?php echo site_url(); ?>';
let items = [];
let editItems = [];
let itemCounter = 0;
let editItemCounter = 0;
let currentDetailPackingId = null;
let currentPackingToDelete = null;

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
});

function loadPackingList(page = 1) {
    const params = new URLSearchParams({
        page: page,
        limit: pageSize,
        filter: currentFilter,
        search: currentSearch
    });

    fetch(`${baseUrl}/packing-list/api/list?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderPackingTable(data.data);
                // Pastikan data pagination ada sebelum mengupdate
                if (data.pagination) {
                    updatePagination(data.pagination);
                } else {
                    // Fallback jika pagination tidak tersedia
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
    
    // Validasi packingList
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
        // Validasi data packing
        if (!packing) return;
        
        const labelStatus = getLabelStatus(packing.status_label);
        const loadingStatus = getLoadingStatus(packing.status_loading);
        
        html += `
            <tr data-status-label="${packing.status_label || ''}" data-status-loading="${packing.status_loading || ''}">
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
                    <span class="badge ${labelStatus.class}">${labelStatus.text}</span>
                </td>
                <td>
                    <span class="badge ${loadingStatus.class}">${loadingStatus.text}</span>
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
                                onclick="printLabel('${packing.no_packing || ''}', ${packing.id || 0})"
                                data-bs-toggle="tooltip" title="Cetak Label">
                            <i class="fas fa-print"></i>
                        </button>
                        <button type="button" class="btn btn-outline-info"
                                onclick="scanPacking(${packing.id || 0})"
                                data-bs-toggle="tooltip" title="Scan">
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

function updatePagination(pagination) {
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationInfo = document.getElementById('paginationInfo');
    
    // Validasi data pagination
    if (!pagination || typeof pagination.total === 'undefined') {
        console.warn('Data pagination tidak valid:', pagination);
        updatePaginationWithFallback(0, currentPage);
        return;
    }
    
    // Update pagination info
    const startItem = (currentPage - 1) * pageSize + 1;
    const endItem = Math.min(currentPage * pageSize, pagination.total);
    paginationInfo.innerHTML = `Menampilkan <strong>${startItem}-${endItem}</strong> dari <strong>${pagination.total}</strong> packing list`;
    
    // Update pagination buttons
    let paginationHtml = '';
    totalPages = pagination.total_pages || 1;
    
    // Jika hanya ada 1 halaman, sembunyikan pagination
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    // Previous button
    paginationHtml += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Adjust start page if we're near the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // First page
    if (startPage > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(1)">1</a>
            </li>
            ${startPage > 2 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
        `;
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>
        `;
    }
    
    // Last page
    if (endPage < totalPages) {
        paginationHtml += `
            ${endPage < totalPages - 1 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${totalPages})">${totalPages}</a>
            </li>
        `;
    }
    
    // Next button
    paginationHtml += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
    
    paginationContainer.innerHTML = paginationHtml;
}

// Fallback function ketika pagination tidak tersedia
function updatePaginationWithFallback(totalItems, currentPage) {
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationInfo = document.getElementById('paginationInfo');
    
    // Hitung total pages berdasarkan total items
    totalPages = Math.ceil(totalItems / pageSize) || 1;
    
    // Update pagination info
    const startItem = (currentPage - 1) * pageSize + 1;
    const endItem = Math.min(currentPage * pageSize, totalItems);
    
    if (totalItems > 0) {
        paginationInfo.innerHTML = `Menampilkan <strong>${startItem}-${endItem}</strong> dari <strong>${totalItems}</strong> packing list`;
    } else {
        paginationInfo.innerHTML = 'Tidak ada data yang ditampilkan';
    }
    
    // Jika hanya ada 1 halaman, sembunyikan pagination
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    // Buat pagination sederhana
    let paginationHtml = '';
    
    // Previous button
    paginationHtml += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Page numbers (sederhana, hanya tampilkan 3 halaman sekitar current page)
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Next button
    paginationHtml += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
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

function performSearch() {
    currentSearch = document.getElementById('searchInput').value.trim();
    currentPage = 1; // Reset to first page when searching
    loadPackingList(currentPage);
}

function applyFilter(status) {
    currentFilter = status;
    currentPage = 1; // Reset to first page when filter changes
    loadPackingList(currentPage);
}

function getLabelStatus(status) {
    const statusMap = {
        'draft': { text: 'Draft', class: 'bg-secondary' },
        'printed': { text: 'Tercetak', class: 'bg-success' },
        'scanned': { text: 'Terkirim', class: 'bg-info' }
    };
    return statusMap[status] || { text: 'Unknown', class: 'bg-secondary' };
}

function getLoadingStatus(status) {
    const statusMap = {
        'pending': { text: 'Pending', class: 'bg-warning' },
        'loading': { text: 'Loading', class: 'bg-primary' },
        'loaded': { text: 'Selesai', class: 'bg-success' }
    };
    return statusMap[status] || { text: 'Unknown', class: 'bg-secondary' };
}

function loadBarangList() {
    fetch(`${baseUrl}/packing-list/api/barang`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderBarangDropdown(data.data);
                renderEditBarangDropdown(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading barang list:', error);
        });
}

function renderBarangDropdown(barangList) {
    const select = document.getElementById('pilihBarang');
    
    // Validasi barangList
    if (!barangList || !Array.isArray(barangList)) {
        select.innerHTML = '<option value="">Gagal memuat data barang</option>';
        return;
    }
    
    let html = '<option value="">Pilih Barang...</option>';
    
    barangList.forEach(barang => {
        if (!barang) return;
        html += `<option value="${barang.id || ''}" data-kode="${barang.kode || ''}" data-nama="${barang.nama || ''}" data-kategori="${barang.kategori || ''}">${barang.kode || ''} - ${barang.nama || ''}</option>`;
    });
    
    select.innerHTML = html;
}

function renderEditBarangDropdown(barangList) {
    const select = document.getElementById('edit_pilihBarang');
    
    // Validasi barangList
    if (!barangList || !Array.isArray(barangList)) {
        select.innerHTML = '<option value="">Gagal memuat data barang</option>';
        return;
    }
    
    let html = '<option value="">Pilih Barang...</option>';
    
    barangList.forEach(barang => {
        if (!barang) return;
        html += `<option value="${barang.id || ''}" data-kode="${barang.kode || ''}" data-nama="${barang.nama || ''}" data-kategori="${barang.kategori || ''}">${barang.kode || ''} - ${barang.nama || ''}</option>`;
    });
    
    select.innerHTML = html;
}

function tambahItem() {
    const selectBarang = document.getElementById('pilihBarang');
    const qtyInput = document.getElementById('qtyBarang');
    
    if (selectBarang.value === '') {
        alert('Pilih barang terlebih dahulu!');
        return;
    }
    
    if (qtyInput.value === '' || parseInt(qtyInput.value) < 1) {
        alert('Masukkan quantity yang valid!');
        return;
    }
    
    const selectedOption = selectBarang.options[selectBarang.selectedIndex];
    const kodeBarang = selectedOption.getAttribute('data-kode');
    const namaBarang = selectedOption.getAttribute('data-nama');
    const kategori = selectedOption.getAttribute('data-kategori');
    const qty = parseInt(qtyInput.value);
    
    // Check if item already exists
    const existingItem = items.find(item => item.kode === kodeBarang);
    if (existingItem) {
        existingItem.qty += qty;
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
        alert('Pilih barang terlebih dahulu!');
        return;
    }
    
    if (qtyInput.value === '' || parseInt(qtyInput.value) < 1) {
        alert('Masukkan quantity yang valid!');
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
        existingItem.qty += qty;
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
        total += item.qty;
        tbody.innerHTML += `
            <tr>
                <td>${item.id + 1}</td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>
                    <span class="badge ${item.kategori === 'Tube' ? 'bg-tube' : 'bg-tire'}">
                        ${item.kategori}
                    </span>
                </td>
                <td class="text-end">${item.qty}</td>
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
        total += item.qty;
        tbody.innerHTML += `
            <tr>
                <td>${item.id + 1}</td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>
                    <span class="badge ${item.kategori === 'Tube' ? 'bg-tube' : 'bg-tire'}">
                        ${item.kategori}
                    </span>
                </td>
                <td class="text-end">${item.qty}</td>
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

function simpanPackingList() {
    if (items.length === 0) {
        alert('Tambahkan minimal satu item!');
        return;
    }
    
    const formData = new FormData(document.getElementById('formBuatPacking'));
    formData.append('items', JSON.stringify(items));
    
    fetch(`${baseUrl}/packing-list/simpan`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Packing list berhasil disimpan!');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('buatPackingModal'));
            modal.hide();
            loadPackingList();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan packing list');
    });
}

function updatePackingList() {
    if (editItems.length === 0) {
        alert('Tambahkan minimal satu item!');
        return;
    }
    
    const formData = new FormData(document.getElementById('formEditPacking'));
    formData.append('items', JSON.stringify(editItems));
    
    fetch(`${baseUrl}/packing-list/update`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Packing list berhasil diupdate!');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('editPackingModal'));
            modal.hide();
            loadPackingList();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate packing list');
    });
}

function resetFormPacking() {
    items = [];
    itemCounter = 0;
    updateTabelItem();
    document.getElementById('formBuatPacking').reset();
    document.getElementById('no_packing').value = 'PL<?php echo sprintf('%03d', (($total_packing ?? 0) + 1)); ?>';
    document.getElementById('tanggal').value = '<?php echo date('Y-m-d'); ?>';
}

function resetEditFormPacking() {
    editItems = [];
    editItemCounter = 0;
    updateEditTabelItem();
    document.getElementById('formEditPacking').reset();
}

function editPacking(id) {
    fetch(`${baseUrl}/packing-list/api/detail/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            } else {
                alert('Gagal memuat data packing list untuk edit');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data untuk edit');
        });
}

function showEditModal(data) {
    // Validasi data
    if (!data) {
        alert('Data packing list tidak valid');
        return;
    }

    // Isi form dengan data yang ada
    document.getElementById('edit_id').value = data.id || '';
    document.getElementById('edit_no_packing').value = data.no_packing || '';
    document.getElementById('edit_tanggal').value = data.tanggal || '';
    document.getElementById('edit_customer').value = data.customer || '';
    document.getElementById('edit_alamat').value = data.alamat || '';
    document.getElementById('edit_keterangan').value = data.keterangan || '';
    
    // Reset dan isi items
    editItems = [];
    editItemCounter = 0;
    
    if (data.items && Array.isArray(data.items)) {
        data.items.forEach((item, index) => {
            editItems.push({
                id: editItemCounter++,
                kode: item.kode || '',
                nama: item.nama || '',
                kategori: item.kategori || '',
                qty: item.qty || 0
            });
        });
    }
    
    updateEditTabelItem();
    
    // Show edit modal
    const modal = new bootstrap.Modal(document.getElementById('editPackingModal'));
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
        alert('Tidak ada packing list yang dipilih untuk dihapus');
        return;
    }
    
    fetch(`${baseUrl}/packing-list/delete/${currentPackingToDelete}`, {
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Packing list berhasil dihapus!');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('konfirmasiHapusModal'));
            modal.hide();
            loadPackingList();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus packing list');
    })
    .finally(() => {
        currentPackingToDelete = null;
    });
}

function detailPacking(id) {
    fetch(`${baseUrl}/packing-list/api/detail/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDetailModal(data.data);
                currentDetailPackingId = id;
            } else {
                alert('Gagal memuat detail packing list');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail');
        });
}

function showDetailModal(data) {
    // Validasi data
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

    const labelStatus = getLabelStatus(data.status_label);
    const loadingStatus = getLoadingStatus(data.status_loading);
    
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
                    <label class="form-label text-muted small mb-1">Status Label</label>
                    <div>
                        <span class="badge ${labelStatus.class}">${labelStatus.text}</span>
                    </div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Status Loading</label>
                    <div>
                        <span class="badge ${loadingStatus.class}">${loadingStatus.text}</span>
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
                        <i class="fas fa-boxes me-2"></i>Daftar Item (${data.total_items || 0} item)
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
                            ${(data.items && Array.isArray(data.items) ? data.items : []).map((item, index) => `
                                <tr>
                                    <td class="text-muted">${index + 1}</td>
                                    <td>
                                        <span class="badge bg-dark">${item.kode || 'N/A'}</span>
                                    </td>
                                    <td class="fw-medium">${item.nama || 'N/A'}</td>
                                    <td>
                                        <span class="badge ${(item.kategori === 'Tube' ? 'bg-tube' : 'bg-tire')}">
                                            ${item.kategori || 'N/A'}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">${item.qty || 0}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold text-primary">${data.total_items || 0}</td>
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

// Helper function untuk format tanggal
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

function printLabel(noPacking, packingId) {
    if (!packingId) {
        alert('ID packing tidak valid');
        return;
    }
    
    fetch(`${baseUrl}/packing-list/api/cetak-label`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `packing_ids[]=${packingId}&type=single`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Label untuk ${noPacking} berhasil digenerate`);
            // Implement print functionality here
            console.log('Label data:', data.data);
        } else {
            alert('Gagal generate label: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat generate label');
    });
}

function printLabelFromDetail() {
    if (currentDetailPackingId) {
        printLabel('PL' + currentDetailPackingId.toString().padStart(3, '0'), currentDetailPackingId);
    } else {
        alert('Tidak ada packing list yang dipilih');
    }
}

function scanPacking(id) {
    if (!id) {
        alert('ID packing tidak valid');
        return;
    }
    // Redirect to scan page with packing ID
    window.location.href = `${baseUrl}/scan?packing_id=${id}`;
}

function printSelectedLabels() {
    const selected = document.querySelectorAll('.packing-checkbox:checked');
    if (selected.length === 0) {
        alert('Pilih packing list yang akan dicetak labelnya!');
        return;
    }
    
    const packingIds = Array.from(selected).map(checkbox => checkbox.value);
    const packingNumbers = Array.from(selected).map(checkbox => {
        const row = checkbox.closest('tr');
        return row ? row.querySelector('td:nth-child(3)').textContent.trim() : 'N/A';
    });
    
    fetch(`${baseUrl}/packing-list/api/cetak-label`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `packing_ids[]=${packingIds.join('&packing_ids[]=')}&type=multiple`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Berhasil generate ${data.data ? data.data.total : packingIds.length} label untuk: ${packingNumbers.join(', ')}`);
            // Implement print functionality here
            console.log('Multiple labels data:', data.data);
        } else {
            alert('Gagal generate label: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat generate label');
    });
}

function refreshData() {
    currentPage = 1;
    currentSearch = '';
    document.getElementById('searchInput').value = '';
    loadPackingList();
    loadBarangList();
    showSuccess('Data berhasil diperbarui');
}

function showError(message) {
    // Implement error notification
    console.error('Error:', message);
    alert('Error: ' + message);
}

function showSuccess(message) {
    // Implement success notification
    console.log('Success:', message);
}
</script>