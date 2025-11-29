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
                <div class="stat-number text-primary"><?php echo $total_barang ?? '1,248'; ?></div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up me-1"></i> +125 dari target
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-warning"><?php echo $stok_minimum ?? '12'; ?></div>
                <div class="stat-label">Stok Minimum</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-exclamation-triangle me-1"></i> Perlu restock
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-success"><?php echo $barang_aktif ?? '1,236'; ?></div>
                <div class="stat-label">Barang Aktif</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check me-1"></i> Tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-info"><?php echo $total_kategori ?? '8'; ?></div>
                <div class="stat-label">Kategori Barang</div>
                <div class="stat-trend">
                    <i class="fas fa-tags me-1"></i> Kategori
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
                            <li><a class="dropdown-item" href="#" data-filter="stok-minimum">Stok Minimum</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="aktif">Barang Aktif</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="nonaktif">Barang Nonaktif</a></li>
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
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Status</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Sample data - replace with actual data from controller
                                $barang_list = [
                                    [
                                        'id' => 1,
                                        'kode_barang' => 'BRG001',
                                        'nama_barang' => 'Bearing 6201ZZ',
                                        'kategori' => 'Bearing',
                                        'stok' => 150,
                                        'stok_minimum' => 20,
                                        'satuan' => 'PCS',
                                        'harga_beli' => 25000,
                                        'harga_jual' => 35000,
                                        'status' => 'aktif',
                                        'deskripsi' => 'Bearing ukuran standar',
                                        'gambar' => base_url('assets/images/products/bearing-6201.jpg')
                                    ],
                                    [
                                        'id' => 2,
                                        'kode_barang' => 'BRG002',
                                        'nama_barang' => 'Seal TC 25x42x7',
                                        'kategori' => 'Seal',
                                        'stok' => 45,
                                        'stok_minimum' => 10,
                                        'satuan' => 'PCS',
                                        'harga_beli' => 15000,
                                        'harga_jual' => 22000,
                                        'status' => 'aktif',
                                        'deskripsi' => 'Oil seal double lip',
                                        'gambar' => base_url('assets/images/products/seal-tc.jpg')
                                    ],
                                    [
                                        'id' => 3,
                                        'kode_barang' => 'BRG003',
                                        'nama_barang' => 'V-Belt A-50',
                                        'kategori' => 'Belt',
                                        'stok' => 8,
                                        'stok_minimum' => 15,
                                        'satuan' => 'PCS',
                                        'harga_beli' => 45000,
                                        'harga_jual' => 65000,
                                        'status' => 'aktif',
                                        'deskripsi' => 'V-belt type A',
                                        'gambar' => base_url('assets/images/products/v-belt.jpg')
                                    ],
                                    [
                                        'id' => 4,
                                        'kode_barang' => 'BRG004',
                                        'nama_barang' => 'Roller Chain 08B-1',
                                        'kategori' => 'Chain',
                                        'stok' => 25,
                                        'stok_minimum' => 5,
                                        'satuan' => 'SET',
                                        'harga_beli' => 120000,
                                        'harga_jual' => 180000,
                                        'status' => 'aktif',
                                        'deskripsi' => 'Roller chain standard',
                                        'gambar' => base_url('assets/images/products/roller-chain.jpg')
                                    ],
                                    [
                                        'id' => 5,
                                        'kode_barang' => 'BRG005',
                                        'nama_barang' => 'Gear Motor 0.5HP',
                                        'kategori' => 'Motor',
                                        'stok' => 3,
                                        'stok_minimum' => 2,
                                        'satuan' => 'UNIT',
                                        'harga_beli' => 850000,
                                        'harga_jual' => 1200000,
                                        'status' => 'nonaktif',
                                        'deskripsi' => 'Gear motor 0.5 horsepower',
                                        'gambar' => base_url('assets/images/products/gear-motor.jpg')
                                    ]
                                ];
                                ?>

                                <?php if(!empty($barang_list)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach($barang_list as $barang): ?>
                                        <tr data-status="<?php echo $barang['status']; ?>" data-stok="<?php echo $barang['stok']; ?>" data-stok-min="<?php echo $barang['stok_minimum']; ?>">
                                            <td>
                                                <input type="checkbox" class="row-checkbox" value="<?php echo $barang['id']; ?>">
                                            </td>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <span class="badge bg-dark"><?php echo $barang['kode_barang']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40 me-3">
                                                        <img src="<?php echo $barang['gambar']; ?>" 
                                                             alt="<?php echo $barang['nama_barang']; ?>" 
                                                             class="rounded" 
                                                             style="width: 40px; height: 40px; object-fit: cover;"
                                                             onerror="this.src='<?php echo base_url('assets/images/placeholder-product.png'); ?>'">
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo $barang['nama_barang']; ?></div>
                                                        <small class="text-muted"><?php echo $barang['deskripsi']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo $barang['kategori']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold <?php echo ($barang['stok'] <= $barang['stok_minimum']) ? 'text-danger' : 'text-success'; ?>">
                                                        <?php echo $barang['stok']; ?>
                                                    </span>
                                                    <?php if($barang['stok'] <= $barang['stok_minimum']): ?>
                                                        <i class="fas fa-exclamation-triangle text-danger ms-2" title="Stok Minimum"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted">Min: <?php echo $barang['stok_minimum']; ?></small>
                                            </td>
                                            <td><?php echo $barang['satuan']; ?></td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    Rp <?php echo number_format($barang['harga_beli'], 0, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-primary fw-bold">
                                                    Rp <?php echo number_format($barang['harga_jual'], 0, ',', '.'); ?>
                                                </span>
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
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="Edit"
                                                            onclick="editBarang(<?php echo $barang['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info"
                                                            data-bs-toggle="tooltip" title="Detail"
                                                            onclick="detailBarang(<?php echo $barang['id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            onclick="hapusBarang(<?php echo $barang['id']; ?>, '<?php echo $barang['nama_barang']; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
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
                                Menampilkan <strong><?php echo count($barang_list); ?></strong> dari <strong><?php echo $total_barang ?? '1,248'; ?></strong> barang
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
            <form id="formTambahBarang" action="<?php echo site_url('barang/tambah'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" required 
                                       placeholder="BRG001" value="BRG<?php echo sprintf('%03d', (count($barang_list) + 1)); ?>">
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
                                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori_id" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="1">Bearing</option>
                                    <option value="2">Seal</option>
                                    <option value="3">Belt</option>
                                    <option value="4">Chain</option>
                                    <option value="5">Motor</option>
                                    <option value="6">Sparepart</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select" id="satuan" name="satuan" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="PCS">PCS</option>
                                    <option value="UNIT">UNIT</option>
                                    <option value="BOX">BOX</option>
                                    <option value="PAK">PAK</option>
                                    <option value="LUSIN">LUSIN</option>
                                    <option value="KG">KG</option>
                                    <option value="METER">METER</option>
                                    <option value="SET">SET</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok Awal</label>
                                <input type="number" class="form-control" id="stok" name="stok" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stok_minimum" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="stok_minimum" name="stok_minimum" value="5" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga_beli" class="form-label">Harga Beli (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="harga_beli" name="harga_beli" required 
                                       placeholder="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga_jual" class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="harga_jual" name="harga_jual" required 
                                       placeholder="0" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi barang..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Barang</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</div>
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

<!-- Modal Detail Barang -->
<div class="modal fade" id="detailBarangModal" tabindex="-1" aria-labelledby="detailBarangModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="detailBarangModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Detail Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="detailBarangContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5" id="loadingDetail">
                    <div class="spinner-border text-kenda-red" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data barang...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* FIX Z-INDEX UNTUK MODAL */
.modal {
    z-index: 1060 !important; /* Lebih tinggi dari sidebar (1050) */
}

.modal-backdrop {
    z-index: 1055 !important; /* Backdrop di atas sidebar tapi di bawah modal */
}

/* Pastikan modal selalu di atas elemen lain */
.modal-content {
    z-index: 1061 !important;
}

/* Style untuk stat cards */
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-top: 4px solid;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.stat-trend {
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

.trend-up {
    color: #28a745;
    background-color: rgba(40, 167, 69, 0.1);
}

.trend-down {
    color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
}

/* Style untuk table */
.table th {
    background-color: var(--kenda-black);
    color: white;
    border-bottom: 2px solid var(--kenda-red);
    font-weight: 600;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
}

.badge {
    font-size: 0.75em;
}

.symbol {
    flex-shrink: 0;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Style untuk modal detail barang */
#detailBarangModal .modal-content {
    border: 2px solid var(--kenda-red);
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

#detailBarangModal .modal-header {
    border-bottom: 2px solid var(--kenda-red);
    padding: 15px 20px;
}

#detailBarangModal .modal-title {
    font-weight: 600;
    font-size: 1.2rem;
}

#detailBarangModal .modal-body {
    background-color: var(--kenda-white);
}

/* Style untuk content detail barang */
.detail-barang-content {
    padding: 20px;
}

.detail-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.detail-image {
    flex-shrink: 0;
    width: 150px;
    height: 150px;
    border: 2px solid var(--kenda-red);
    border-radius: 8px;
    overflow: hidden;
    background-color: var(--kenda-light-gray);
}

.detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.detail-info {
    flex: 1;
}

.detail-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--kenda-black);
    margin-bottom: 5px;
}

.detail-kode {
    background-color: var(--kenda-black);
    color: var(--kenda-white);
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 10px;
}

.detail-description {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.5;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.detail-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid var(--kenda-red);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-card h6 {
    font-weight: 600;
    color: var(--kenda-black);
    margin-bottom: 15px;
    font-size: 1rem;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 8px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f8f8f8;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #666;
    font-weight: 500;
    font-size: 0.9rem;
}

.detail-value {
    font-weight: 600;
    color: var(--kenda-black);
    text-align: right;
}

.detail-badge {
    font-size: 0.8rem;
    padding: 4px 10px;
    border-radius: 4px;
}

.stok-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stok-warning {
    color: #dc3545;
    font-size: 0.8rem;
}

.stok-safe {
    color: #28a745;
    font-size: 0.8rem;
}

.detail-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

/* Responsive design untuk modal detail */
@media (max-width: 768px) {
    .detail-header {
        flex-direction: column;
        text-align: center;
    }
    
    .detail-image {
        align-self: center;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .detail-actions {
        flex-direction: column;
    }
    
    .detail-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .detail-barang-content {
        padding: 15px;
    }
    
    .detail-card {
        padding: 15px;
    }
    
    .detail-title {
        font-size: 1.3rem;
    }
    
    .detail-image {
        width: 120px;
        height: 120px;
    }
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .stat-card {
        padding: 15px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-sm > .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }
}

/* FIX UNTUK DROPDOWN DI MODAL */
.modal .dropdown-menu {
    z-index: 1062 !important;
}

/* Pastikan modal backdrop menutupi sidebar */
.modal-backdrop.show {
    opacity: 0.5;
    background-color: #000;
}
</style>

<script>
// FIX Z-INDEX DENGAN JAVASCRIPT
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Fix z-index untuk modal saat ditampilkan
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function () {
            // Pastikan modal di atas sidebar
            this.style.zIndex = '1060';
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.style.zIndex = '1055';
            });
        });
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

    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = document.getElementById('selectAll').checked;
        });
    });

    // Filter functionality
    document.querySelectorAll('.dropdown-item[data-filter]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var filter = this.getAttribute('data-filter');
            applyFilter(filter);
        });
    });

    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        exportData();
    });

    // Close sidebar when modal is open (mobile)
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const sidebarBackdrop = document.getElementById('sidebarBackdrop');
                if (sidebar && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
                }
            }
        });
    });
});

function performSearch() {
    var searchTerm = document.getElementById('searchInput').value.toLowerCase();
    var rows = document.querySelectorAll('#barangTable tbody tr');
    
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function applyFilter(filter) {
    var rows = document.querySelectorAll('#barangTable tbody tr');
    
    rows.forEach(function(row) {
        var status = row.getAttribute('data-status');
        var stok = parseInt(row.getAttribute('data-stok'));
        var stokMin = parseInt(row.getAttribute('data-stok-min'));
        
        switch(filter) {
            case 'all':
                row.style.display = '';
                break;
            case 'stok-minimum':
                row.style.display = stok <= stokMin ? '' : 'none';
                break;
            case 'aktif':
                row.style.display = status === 'aktif' ? '' : 'none';
                break;
            case 'nonaktif':
                row.style.display = status === 'nonaktif' ? '' : 'none';
                break;
        }
    });
}

function editBarang(id) {
    // Implementation for edit barang
    alert('Edit barang dengan ID: ' + id);
    // You can implement AJAX call to load edit form
}

function detailBarang(id) {
    var modal = new bootstrap.Modal(document.getElementById('detailBarangModal'));
    
    // Show loading state
    document.getElementById('detailBarangContent').innerHTML = `
        <div class="text-center py-5" id="loadingDetail">
            <div class="spinner-border text-kenda-red" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat data barang...</p>
        </div>
    `;
    
    modal.show();
    
    // Simulate AJAX call - replace with actual API call
    setTimeout(function() {
        // Sample data - replace with actual data from your database
        const barangData = {
            id: id,
            kode_barang: 'BRG001',
            nama_barang: 'Bearing 6201ZZ',
            kategori: 'Bearing',
            stok: 150,
            stok_minimum: 20,
            satuan: 'PCS',
            harga_beli: 25000,
            harga_jual: 35000,
            status: 'aktif',
            deskripsi: 'Bearing ukuran standar untuk berbagai aplikasi industri. Tahan lama dan berkualitas tinggi.',
            gambar: '<?php echo base_url('assets/images/placeholder-product.png'); ?>',
            supplier: 'PT. Supplier Utama',
            created_at: '2024-01-15 10:30:00',
            updated_at: '2024-03-20 14:25:00'
        };
        
        renderDetailBarang(barangData);
    }, 1000);
}

function renderDetailBarang(data) {
    const stokStatus = data.stok <= data.stok_minimum ? 'danger' : 'success';
    const stokText = data.stok <= data.stok_minimum ? 'Stok Minimum' : 'Stok Aman';
    const statusBadge = data.status === 'aktif' ? 'bg-success' : 'bg-danger';
    const statusText = data.status === 'aktif' ? 'Aktif' : 'Nonaktif';
    
    document.getElementById('detailBarangContent').innerHTML = `
        <div class="detail-barang-content">
            <!-- Header dengan gambar dan info dasar -->
            <div class="detail-header">
                <div class="detail-image">
                    <img src="${data.gambar}" alt="${data.nama_barang}" 
                         onerror="this.src='<?php echo base_url('assets/images/placeholder-product.png'); ?>'">
                </div>
                <div class="detail-info">
                    <h1 class="detail-title">${data.nama_barang}</h1>
                    <span class="detail-kode">${data.kode_barang}</span>
                    <p class="detail-description mt-2">${data.deskripsi}</p>
                </div>
            </div>
            
            <!-- Grid informasi -->
            <div class="detail-grid">
                <!-- Informasi Stok -->
                <div class="detail-card">
                    <h6><i class="fas fa-boxes me-2"></i>Informasi Stok</h6>
                    <div class="detail-row">
                        <span class="detail-label">Stok Saat Ini</span>
                        <div class="stok-indicator">
                            <span class="detail-value ${stokStatus === 'danger' ? 'text-danger' : 'text-success'}">
                                ${data.stok} ${data.satuan}
                            </span>
                            ${data.stok <= data.stok_minimum ? 
                                '<i class="fas fa-exclamation-triangle text-danger" title="Stok Minimum"></i>' : 
                                '<i class="fas fa-check-circle text-success" title="Stok Aman"></i>'
                            }
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Stok Minimum</span>
                        <span class="detail-value">${data.stok_minimum} ${data.satuan}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status Stok</span>
                        <span class="detail-badge bg-${stokStatus}">${stokText}</span>
                    </div>
                </div>
                
                <!-- Informasi Harga -->
                <div class="detail-card">
                    <h6><i class="fas fa-tag me-2"></i>Informasi Harga</h6>
                    <div class="detail-row">
                        <span class="detail-label">Harga Beli</span>
                        <span class="detail-value text-success">Rp ${formatRupiah(data.harga_beli)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Harga Jual</span>
                        <span class="detail-value text-primary">Rp ${formatRupiah(data.harga_jual)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Margin</span>
                        <span class="detail-value text-info">Rp ${formatRupiah(data.harga_jual - data.harga_beli)}</span>
                    </div>
                </div>
                
                <!-- Informasi Lainnya -->
                <div class="detail-card">
                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Lainnya</h6>
                    <div class="detail-row">
                        <span class="detail-label">Kategori</span>
                        <span class="detail-badge bg-secondary">${data.kategori}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Satuan</span>
                        <span class="detail-value">${data.satuan}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-badge ${statusBadge}">${statusText}</span>
                    </div>
                </div>
                
                <!-- Informasi Supplier & Tanggal -->
                <div class="detail-card">
                    <h6><i class="fas fa-calendar me-2"></i>Informasi Sistem</h6>
                    <div class="detail-row">
                        <span class="detail-label">Supplier</span>
                        <span class="detail-value">${data.supplier || '-'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Dibuat Pada</span>
                        <span class="detail-value">${formatDate(data.created_at)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Diupdate Pada</span>
                        <span class="detail-value">${formatDate(data.updated_at)}</span>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="detail-actions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <button type="button" class="btn btn-kenda" onclick="editBarang(${data.id})">
                    <i class="fas fa-edit me-2"></i>Edit Barang
                </button>
                <button type="button" class="btn btn-kenda-red" onclick="hapusBarang(${data.id}, '${data.nama_barang}')">
                    <i class="fas fa-trash me-2"></i>Hapus Barang
                </button>
            </div>
        </div>
    `;
}

function hapusBarang(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus barang "' + nama + '"?')) {
        // Implementation for delete barang
        alert('Barang "' + nama + '" berhasil dihapus');
        // You can implement AJAX call to delete the item
    }
}

function exportData() {
    // Implementation for export data
    alert('Fitur export data akan diimplementasikan');
    // You can implement export to Excel, PDF, etc.
}

// Helper functions
function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Handle modal hidden event
document.getElementById('detailBarangModal').addEventListener('hidden.bs.modal', function () {
    // Reset content when modal is closed
    document.getElementById('detailBarangContent').innerHTML = `
        <div class="text-center py-5" id="loadingDetail">
            <div class="spinner-border text-kenda-red" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat data barang...</p>
        </div>
    `;
});
</script>
