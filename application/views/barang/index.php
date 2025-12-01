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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stokAwalModal">
                        <i class="fas fa-database me-2"></i>Input Stok Awal
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#barangMasukModal">
                        <i class="fas fa-arrow-down me-2"></i>Barang Masuk
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
                        <input type="text" class="form-control" placeholder="Cari barang..." id="searchInput">
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
                                    <?php $no = 1; ?>
                                    <?php foreach($barang_list as $barang): ?>
                                        <?php 
                                            $barang_id = htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8');
                                            $barang_nama = htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8');
                                            $barang_kategori = htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8');
                                            $barang_satuan = htmlspecialchars($barang['satuan'] ?? 'PCS', ENT_QUOTES, 'UTF-8');
                                            $barang_stok = intval($barang['stok'] ?? 0);
                                            $barang_stok_min = intval($barang['stok_minimum'] ?? 0);
                                            $barang_deskripsi = htmlspecialchars($barang['deskripsi'] ?? '-', ENT_QUOTES, 'UTF-8');
                                        ?>
                                        <tr data-kategori="<?php echo $barang_kategori; ?>" 
                                            data-stok="<?php echo $barang_stok; ?>" 
                                            data-stok-min="<?php echo $barang_stok_min; ?>"
                                            data-id="<?php echo $barang_id; ?>">
                                            <td>
                                                <input type="checkbox" class="row-checkbox" value="<?php echo $barang_id; ?>">
                                            </td>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <span class="badge bg-dark"><?php echo $barang_id; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40 me-3">
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-<?php echo $barang_kategori == 'Tube' ? 'cog' : 'tire'; ?> text-<?php echo $barang_kategori == 'Tube' ? 'primary' : 'success'; ?>"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo $barang_nama; ?></div>
                                                        <small class="text-muted"><?php echo $barang_deskripsi; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $barang_kategori == 'Tube' ? 'bg-primary' : 'bg-success'; ?>">
                                                    <?php echo $barang_kategori; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold <?php echo ($barang_stok <= $barang_stok_min) ? 'text-danger' : 'text-success'; ?>">
                                                        <?php echo $barang_stok; ?>
                                                    </span>
                                                    <?php if($barang_stok <= $barang_stok_min): ?>
                                                        <i class="fas fa-exclamation-triangle text-danger ms-2" title="Stok Minimum"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo $barang_satuan; ?></td>
                                            <td>
                                                <span class="text-muted"><?php echo $barang_stok_min; ?></span>
                                            </td>
                                            <td>
                                                <?php if($barang['status'] == 'aktif'): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info" 
                                                            data-bs-toggle="tooltip" title="Detail"
                                                            onclick="showDetailBarang('<?php echo $barang_id; ?>')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="Edit"
                                                            onclick="editBarang('<?php echo $barang_id; ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            onclick="konfirmasiHapus('<?php echo $barang_id; ?>', '<?php echo $barang_nama; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
                                                <button class="btn btn-kenda" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
                                                    <i class="fas fa-plus me-2"></i>Tambah Barang Pertama
                                                </button>
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
                                Menampilkan <strong><?php echo count($barang_list); ?></strong> dari <strong><?php echo $total_barang; ?></strong> barang
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm justify-content-end mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
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
                                       placeholder="Contoh: TUB001, TIR001">
                                <div class="form-text">Kode unik untuk barang (max 20 karakter)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required 
                                       placeholder="Nama barang">
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
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok_minimum" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="stok_minimum" name="stok_minimum" value="5" min="0">
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
                                  placeholder="Deskripsi barang..."></textarea>
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

<!-- Modal Input Stok Awal (untuk dari table) -->
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
                                       placeholder="Nama barang">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="edit_kode_display" readonly>
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
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_stok_minimum" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="edit_stok_minimum" name="stok_minimum" min="0">
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
                                  placeholder="Deskripsi barang..."></textarea>
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
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
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

<style>
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
</style>

<script>
const baseUrl = '<?php echo site_url(); ?>';
let barangDetailData = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
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
            applyFilter(this.getAttribute('data-filter'));
        });
    });

    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', exportData);

    // Setup form handlers
    setupFormHandlers();
});

function performSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#barangTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
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
    fetch(`${baseUrl}/gudang/api_detail_barang/${kodeBarang}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                barangDetailData = data.data;
                renderDetailModal(data.data);
            } else {
                showError('Gagal memuat detail barang: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat detail barang');
        });
}

function renderDetailModal(data) {
    const statusBadge = data.status === 'aktif' 
        ? '<span class="badge bg-success">Aktif</span>' 
        : '<span class="badge bg-danger">Nonaktif</span>';
    
    const kategoriBadge = data.kategori === 'Tube' 
        ? '<span class="badge bg-primary">Tube</span>' 
        : data.kategori === 'Tire' 
            ? '<span class="badge bg-success">Tire</span>' 
            : '<span class="badge bg-info">Accessories</span>';
    
    const stokClass = data.stok <= (data.stok_minimum || 0) ? 'text-danger' : 'text-success';
    const stokWarning = data.stok <= (data.stok_minimum || 0) 
        ? '<span class="badge bg-warning ms-2"><i class="fas fa-exclamation-triangle me-1"></i>Stok Minimum</span>' 
        : '';
    
    const detailContent = `
        <div class="container-fluid">
            <!-- Header Info -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-${data.kategori === 'Tube' ? 'cog' : data.kategori === 'Tire' ? 'tire' : 'box'} fa-2x text-${data.kategori === 'Tube' ? 'primary' : data.kategori === 'Tire' ? 'success' : 'info'}"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">${data.nama_barang}</h4>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark">${data.kode_barang}</span>
                                ${kategoriBadge}
                                ${statusBadge}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <h3 class="${stokClass}">${data.stok || 0} ${data.satuan || 'PCS'}</h3>
                    <small class="text-muted">Stok Minimum: ${data.stok_minimum || 0} ${data.satuan || 'PCS'}</small>
                    ${stokWarning}
                </div>
            </div>
            
            <!-- Information Grid -->
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">${data.kategori}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Satuan</div>
                        <div class="info-value">${data.satuan || 'PCS'}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Stok Minimum</div>
                        <div class="info-value">${data.stok_minimum || 0} ${data.satuan || 'PCS'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-label">Status</div>
                        <div class="info-value">${data.status === 'aktif' ? 'Aktif' : 'Nonaktif'}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Dibuat</div>
                        <div class="info-value">${formatDateTime(data.created_at)}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Diupdate</div>
                        <div class="info-value">${formatDateTime(data.updated_at)}</div>
                    </div>
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="info-group">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value">${data.deskripsi || '-'}</div>
                    </div>
                </div>
            </div>
            
            <!-- Stock Management Section -->
            <div class="stock-form-container mt-4">
                <h5 class="mb-3">
                    <i class="fas fa-warehouse me-2"></i>Kelola Stok
                </h5>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="info-group">
                            <div class="info-label">Stok Saat Ini</div>
                            <div class="info-value fw-bold ${stokClass}">${data.stok || 0} ${data.satuan || 'PCS'}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-group">
                            <div class="info-label">Stok Minimum</div>
                            <div class="info-value">${data.stok_minimum || 0} ${data.satuan || 'PCS'}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-group">
                            <div class="info-label">Status Stok</div>
                            <div class="info-value">
                                ${data.stok <= (data.stok_minimum || 0) 
                                    ? '<span class="badge bg-warning">Perlu Restock</span>' 
                                    : '<span class="badge bg-success">Aman</span>'}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary btn-stok-action" onclick="openStokAwalFromDetail('${data.kode_barang}', '${data.nama_barang}', '${data.kategori}', '${data.satuan || 'PCS'}')">
                                <i class="fas fa-database me-2"></i>Input Stok Awal
                            </button>
                            <small class="text-muted mt-1">Untuk barang baru tanpa stok</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-grid">
                            <button type="button" class="btn btn-success btn-stok-action" onclick="openBarangMasukFromDetail('${data.kode_barang}', '${data.nama_barang}', '${data.kategori}', '${data.satuan || 'PCS'}', ${data.stok || 0})">
                                <i class="fas fa-arrow-down me-2"></i>Barang Masuk
                            </button>
                            <small class="text-muted mt-1">Tambah stok barang</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="d-grid">
                            <button type="button" class="btn btn-warning btn-stok-action" onclick="openBarangKeluarFromDetail('${data.kode_barang}', '${data.nama_barang}', '${data.kategori}', '${data.satuan || 'PCS'}', ${data.stok || 0})">
                                <i class="fas fa-arrow-up me-2"></i>Barang Keluar
                            </button>
                            <small class="text-muted mt-1">Kurangi stok barang</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-grid">
                            <button type="button" class="btn btn-info btn-stok-action" onclick="openAdjustmentFromDetail('${data.kode_barang}', '${data.nama_barang}', '${data.kategori}', '${data.satuan || 'PCS'}', ${data.stok || 0})">
                                <i class="fas fa-exchange-alt me-2"></i>Adjustment Stok
                            </button>
                            <small class="text-muted mt-1">Koreksi stok</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Tutup
                        </button>
                        <button type="button" class="btn btn-kenda" onclick="editBarang('${data.kode_barang}')" data-bs-dismiss="modal">
                            <i class="fas fa-edit me-2"></i>Edit Barang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('detailBarangContent').innerHTML = detailContent;
    document.getElementById('detailBarangModalLabel').innerHTML = `<i class="fas fa-eye me-2"></i>Detail - ${data.nama_barang}`;
    
    const detailModal = new bootstrap.Modal(document.getElementById('detailBarangModal'));
    detailModal.show();
}

function openStokAwalFromDetail(kodeBarang, namaBarang, kategori, satuan) {
    document.getElementById('stok_awal_kode_barang_hidden').value = kodeBarang;
    document.getElementById('stok_awal_kode_barang_display').value = kodeBarang;
    document.getElementById('stok_awal_nama_barang').value = namaBarang;
    document.getElementById('stok_awal_kategori').value = kategori;
    document.getElementById('stok_awal_satuan').value = satuan;
    
    const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailBarangModal'));
    detailModal.hide();
    
    setTimeout(() => {
        const stokAwalModal = new bootstrap.Modal(document.getElementById('stokAwalModal'));
        stokAwalModal.show();
    }, 300);
}

function openBarangMasukFromDetail(kodeBarang, namaBarang, kategori, satuan, stokSekarang) {
    // Implementation for barang masuk modal
    alert(`Barang Masuk untuk: ${namaBarang}\nStok sekarang: ${stokSekarang}`);
    // You would open the barang masuk modal here
}

function openBarangKeluarFromDetail(kodeBarang, namaBarang, kategori, satuan, stokSekarang) {
    // Implementation for barang keluar modal
    alert(`Barang Keluar untuk: ${namaBarang}\nStok sekarang: ${stokSekarang}`);
}

function openAdjustmentFromDetail(kodeBarang, namaBarang, kategori, satuan, stokSekarang) {
    // Implementation for adjustment modal
    alert(`Adjustment Stok untuk: ${namaBarang}\nStok sekarang: ${stokSekarang}`);
}

function editBarang(kodeBarang) {
    fetch(`${baseUrl}/gudang/api_detail_barang/${kodeBarang}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the edit form
                document.getElementById('edit_kode_barang').value = data.data.kode_barang;
                document.getElementById('edit_kode_display').value = data.data.kode_barang;
                document.getElementById('edit_nama_barang').value = data.data.nama_barang;
                document.getElementById('edit_kategori').value = data.data.kategori;
                document.getElementById('edit_satuan').value = data.data.satuan || 'PCS';
                document.getElementById('edit_stok_minimum').value = data.data.stok_minimum || 0;
                document.getElementById('edit_status').value = data.data.status;
                document.getElementById('edit_deskripsi').value = data.data.deskripsi || '';
                
                // Close detail modal if open
                const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailBarangModal'));
                if (detailModal) detailModal.hide();
                
                // Show edit modal
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
    document.getElementById('namaBarangHapus').textContent = nama;
    
    document.getElementById('btnHapusBarang').onclick = function() {
        hapusBarang(kodeBarang, nama);
    };
    
    const konfirmasiModal = new bootstrap.Modal(document.getElementById('konfirmasiHapusModal'));
    konfirmasiModal.show();
}

function hapusBarang(kodeBarang, nama) {
    fetch(`${baseUrl}/gudang/hapus_barang/${kodeBarang}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({kode_barang: kodeBarang})
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

function exportData() {
    window.location.href = `${baseUrl}/gudang/export_barang`;
}

function formatDateTime(dateTime) {
    if (!dateTime) return '-';
    const date = new Date(dateTime);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        timer: 3000
    });
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: message,
        timer: 2000
    });
}

function setupFormHandlers() {
    // Tambah Barang Form
    const formTambahBarang = document.getElementById('formTambahBarang');
    if (formTambahBarang) {
        formTambahBarang.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(`${baseUrl}/gudang/tambah_barang`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
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
            const formData = new FormData(this);
            
            fetch(`${baseUrl}/gudang/stok_awal`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
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
            const formData = new FormData(this);
            
            fetch(`${baseUrl}/gudang/update_barang`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
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
                console.error('Error:', error);
                showError('Terjadi kesalahan saat mengupdate barang');
            });
        });
    }
}

// Reset form when modal is closed
document.getElementById('tambahBarangModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('formTambahBarang')?.reset();
});

document.getElementById('stokAwalModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('formStokAwal')?.reset();
});

document.getElementById('editBarangModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('formEditBarang')?.reset();
});
</script>
