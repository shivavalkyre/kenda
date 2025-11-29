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
                    <i class="fas fa-arrow-up me-1"></i> Stok tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-info"><?php echo $total_tube ?? '856'; ?></div>
                <div class="stat-label">Total Tube</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check me-1"></i> Tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100">
                <div class="stat-number text-success"><?php echo $total_tire ?? '392'; ?></div>
                <div class="stat-label">Total Tire</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-check me-1"></i> Tersedia
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
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Sample data - replace with actual data from controller
                                $barang_list = [
                                    [
                                        'id' => 1,
                                        'kode_barang' => 'TUB001',
                                        'nama_barang' => 'Tube Standard 17"',
                                        'kategori' => 'Tube',
                                        'stok' => 350,
                                        'stok_minimum' => 50,
                                        'satuan' => 'PCS',
                                        'status' => 'aktif',
                                        'deskripsi' => 'Tube untuk ban 17 inch'
                                    ],
                                    [
                                        'id' => 2,
                                        'kode_barang' => 'TIR001',
                                        'nama_barang' => 'Tire Radial 205/55/R16',
                                        'kategori' => 'Tire',
                                        'stok' => 150,
                                        'stok_minimum' => 20,
                                        'satuan' => 'PCS',
                                        'status' => 'aktif',
                                        'deskripsi' => 'Ban radial ukuran 205/55/R16'
                                    ],
                                    [
                                        'id' => 3,
                                        'kode_barang' => 'TUB002',
                                        'nama_barang' => 'Tube Heavy Duty 19"',
                                        'kategori' => 'Tube',
                                        'stok' => 8,
                                        'stok_minimum' => 15,
                                        'satuan' => 'PCS',
                                        'status' => 'aktif',
                                        'deskripsi' => 'Tube heavy duty untuk truck'
                                    ],
                                    [
                                        'id' => 4,
                                        'kode_barang' => 'TIR002',
                                        'nama_barang' => 'Tire Offroad 265/70/R16',
                                        'kategori' => 'Tire',
                                        'stok' => 25,
                                        'stok_minimum' => 5,
                                        'satuan' => 'PCS',
                                        'status' => 'aktif',
                                        'deskripsi' => 'Ban offroad ukuran 265/70/R16'
                                    ],
                                    [
                                        'id' => 5,
                                        'kode_barang' => 'TUB003',
                                        'nama_barang' => 'Tube Racing 15"',
                                        'kategori' => 'Tube',
                                        'stok' => 45,
                                        'stok_minimum' => 10,
                                        'satuan' => 'PCS',
                                        'status' => 'aktif',
                                        'deskripsi' => 'Tube racing untuk mobil sport'
                                    ]
                                ];
                                ?>

                                <?php if(!empty($barang_list)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach($barang_list as $barang): ?>
                                        <tr data-kategori="<?php echo $barang['kategori']; ?>" data-stok="<?php echo $barang['stok']; ?>" data-stok-min="<?php echo $barang['stok_minimum']; ?>">
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
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-<?php echo $barang['kategori'] == 'Tube' ? 'cog' : 'tire'; ?> text-<?php echo $barang['kategori'] == 'Tube' ? 'primary' : 'success'; ?>"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo $barang['nama_barang']; ?></div>
                                                        <small class="text-muted"><?php echo $barang['deskripsi']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $barang['kategori'] == 'Tube' ? 'bg-tube' : 'bg-tire'; ?>">
                                                    <?php echo $barang['kategori']; ?>
                                                </span>
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
                                            </td>
                                            <td><?php echo $barang['satuan']; ?></td>
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
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="Edit"
                                                            onclick="editBarang(<?php echo $barang['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <!-- Tombol Detail dihilangkan sesuai permintaan -->
                                                    <button type="button" class="btn btn-outline-danger"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            onclick="konfirmasiHapus(<?php echo $barang['id']; ?>, '<?php echo $barang['nama_barang']; ?>')">
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
            <form id="formTambahBarang" action="<?php echo site_url('barang/tambah'); ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" required 
                                       placeholder="TUB001" value="TUB<?php echo sprintf('%03d', (count($barang_list) + 1)); ?>">
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
            <form id="formEditBarang" action="<?php echo site_url('barang/edit'); ?>" method="POST">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_kode_barang" name="kode_barang" required 
                                       placeholder="TUB001">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama_barang" name="nama_barang" required 
                                       placeholder="Nama barang">
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
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="edit_stok" name="stok" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_stok_minimum" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="edit_stok_minimum" name="stok_minimum" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
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
        var kategori = row.getAttribute('data-kategori');
        var stok = parseInt(row.getAttribute('data-stok'));
        var stokMin = parseInt(row.getAttribute('data-stok-min'));
        
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

function editBarang(id) {
    // In a real application, you would fetch the data from the server
    // For this example, we'll use the sample data
    var barangList = <?php echo json_encode($barang_list); ?>;
    var barang = barangList.find(item => item.id == id);
    
    if (barang) {
        // Populate the edit form with the barang data
        document.getElementById('edit_id').value = barang.id;
        document.getElementById('edit_kode_barang').value = barang.kode_barang;
        document.getElementById('edit_nama_barang').value = barang.nama_barang;
        document.getElementById('edit_kategori').value = barang.kategori;
        document.getElementById('edit_satuan').value = barang.satuan;
        document.getElementById('edit_stok').value = barang.stok;
        document.getElementById('edit_stok_minimum').value = barang.stok_minimum;
        document.getElementById('edit_status').value = barang.status;
        document.getElementById('edit_deskripsi').value = barang.deskripsi;
        
        // Show the edit modal
        var editModal = new bootstrap.Modal(document.getElementById('editBarangModal'));
        editModal.show();
    }
}

function konfirmasiHapus(id, nama) {
    // Set the barang name in the confirmation modal
    document.getElementById('namaBarangHapus').textContent = nama;
    
    // Set the delete button to delete the correct item
    document.getElementById('btnHapusBarang').onclick = function() {
        hapusBarang(id, nama);
    };
    
    // Show the confirmation modal
    var konfirmasiModal = new bootstrap.Modal(document.getElementById('konfirmasiHapusModal'));
    konfirmasiModal.show();
}

function hapusBarang(id, nama) {
    // Implementation for delete barang
    // In a real application, you would make an AJAX call to delete the item
    
    // Close the confirmation modal
    var konfirmasiModal = bootstrap.Modal.getInstance(document.getElementById('konfirmasiHapusModal'));
    konfirmasiModal.hide();
    
    // Show success message
    alert('Barang "' + nama + '" berhasil dihapus');
    
    // In a real application, you would refresh the table or remove the row
    console.log('Barang dengan ID ' + id + ' dihapus');
}

function exportData() {
    // Implementation for export data
    alert('Fitur export data akan diimplementasikan');
}
</script>