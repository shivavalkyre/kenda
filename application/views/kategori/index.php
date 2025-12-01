<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
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

<!-- Modal Detail Kategori -->
<div class="modal fade" id="detailKategoriModal" tabindex="-1" aria-labelledby="detailKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title mb-0" id="detailKategoriModalLabel">
                    <i class="fas fa-eye me-2"></i>Detail Kategori
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="detailKategoriContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer bg-light py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
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
</style>

<script>
const baseUrl = '<?php echo site_url(); ?>';

document.addEventListener('DOMContentLoaded', function() {
    loadKategoriList();
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
            var status = this.getAttribute('data-status');
            applyFilter(status);
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

function loadKategoriList() {
    fetch(`${baseUrl}/gudang/api_list_kategori`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderKategoriTable(data.data);
            } else {
                showError('Gagal memuat data kategori');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data');
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
    
    if (kategoriList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-tags fa-3x mb-3"></i>
                        <p>Belum ada kategori barang</p>
                        <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    kategoriList.forEach((kategori, index) => {
        const statusBadge = kategori.status === 'active' ? 
            '<span class="badge bg-success">Aktif</span>' : 
            '<span class="badge bg-secondary">Nonaktif</span>';
        
        html += `
            <tr data-status="${kategori.status}">
                <td>${index + 1}</td>
                <td>
                    <span class="badge bg-dark">${kategori.kode_kategori}</span>
                </td>
                <td class="fw-bold">${kategori.nama_kategori}</td>
                <td>${kategori.deskripsi || '-'}</td>
                <td>
                    <span class="fw-bold text-primary">${kategori.jumlah_barang || 0}</span> barang
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
                                onclick="hapusKategori(${kategori.id}, '${kategori.nama_kategori}')"
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

function simpanKategori() {
    const formData = new FormData(document.getElementById('formTambahKategori'));
    
    fetch(`${baseUrl}/gudang/simpan_kategori`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            // Close modal and refresh data
            const modal = bootstrap.Modal.getInstance(document.getElementById('tambahKategoriModal'));
            modal.hide();
            loadKategoriList();
            loadStatistics();
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menyimpan kategori');
    });
}

function editKategori(id) {
    fetch(`${baseUrl}/gudang/api_detail_kategori/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            } else {
                showError('Gagal memuat data kategori');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data');
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
    
    fetch(`${baseUrl}/gudang/update_kategori`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            // Close modal and refresh data
            const modal = bootstrap.Modal.getInstance(document.getElementById('editKategoriModal'));
            modal.hide();
            loadKategoriList();
            loadStatistics();
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat mengupdate kategori');
    });
}

function detailKategori(id) {
    fetch(`${baseUrl}/gudang/api_detail_kategori/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDetailModal(data.data);
            } else {
                showError('Gagal memuat detail kategori');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat detail');
        });
}

function showDetailModal(data) {
    const statusBadge = data.status === 'active' ? 
        '<span class="badge bg-success">Aktif</span>' : 
        '<span class="badge bg-secondary">Nonaktif</span>';
    
    // Handle barang terbaru data structure
    let barangTerbaruHTML = '';
    if (data.barang_terbaru && data.barang_terbaru.length > 0) {
        barangTerbaruHTML = `
        <div class="row mt-4">
            <div class="col-12">
                <div class="section-header mb-3">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Barang Terbaru (${data.barang_terbaru.length} barang)
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.barang_terbaru.map(barang => `
                                <tr>
                                    <td><span class="badge bg-dark">${barang.kode_barang || '-'}</span></td>
                                    <td>${barang.nama_barang || '-'}</td>
                                    <td><span class="badge ${barang.stok <= barang.stok_minimum ? 'bg-warning' : 'bg-success'}">${barang.stok || 0}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        `;
    }
    
    const modalContent = `
        <div class="row">
            <div class="col-md-6">
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Kode Kategori</label>
                    <div class="fw-bold text-dark">${data.kode_kategori}</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Nama Kategori</label>
                    <div class="fw-bold text-dark">${data.nama_kategori}</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Status</label>
                    <div>${statusBadge}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Jumlah Barang</label>
                    <div class="fw-bold text-primary">${data.jumlah_barang || 0} barang</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Dibuat Pada</label>
                    <div class="text-dark">${formatTanggal(data.created_at)}</div>
                </div>
                <div class="info-group mb-3">
                    <label class="form-label text-muted small mb-1">Diupdate Pada</label>
                    <div class="text-dark">${formatTanggal(data.updated_at)}</div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="info-group">
                    <label class="form-label text-muted small mb-1">Deskripsi</label>
                    <div class="text-dark">${data.deskripsi || '<span class="text-muted">-</span>'}</div>
                </div>
            </div>
        </div>
        
        ${barangTerbaruHTML}
    `;
    
    document.getElementById('detailKategoriContent').innerHTML = modalContent;
    document.getElementById('detailKategoriModalLabel').innerHTML = `<i class="fas fa-eye me-2"></i>Detail - ${data.nama_kategori}`;
    
    const modal = new bootstrap.Modal(document.getElementById('detailKategoriModal'));
    modal.show();
}

function hapusKategori(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus kategori "${nama}"?`)) {
        fetch(`${baseUrl}/gudang/hapus_kategori/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    loadKategoriList();
                    loadStatistics();
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menghapus kategori');
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
    loadKategoriList();
    loadStatistics();
    showSuccess('Data berhasil diperbarui');
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

function showError(message) {
    // You can replace this with a toast notification
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        timer: 3000
    });
}

function showSuccess(message) {
    // You can replace this with a toast notification
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: message,
        timer: 2000
    });
}
</script>
