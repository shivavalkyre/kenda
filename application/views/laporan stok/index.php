<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-bar me-2"></i>Laporan Stok
                </h1>
                <p class="text-muted">Monitoring dan laporan stok barang gudang</p>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="filterKategori" class="form-label">Kategori</label>
                            <select class="form-select" id="filterKategori">
                                <option value="all">Semua Kategori</option>
                                <option value="Tube">Tube</option>
                                <option value="Tire">Tire</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="filterStatus" class="form-label">Status Stok</label>
                            <select class="form-select" id="filterStatus">
                                <option value="all">Semua Status</option>
                                <option value="aman">Stok Aman</option>
                                <option value="minimum">Stok Minimum</option>
                                <option value="habis">Stok Habis</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="filterTanggal" class="form-label">Periode</label>
                            <input type="month" class="form-control" id="filterTanggal" value="<?php echo date('Y-m'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3 d-flex align-items-end">
                            <button class="btn btn-kenda w-100" onclick="applyFilters()">
                                <i class="fas fa-filter me-2"></i>Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number text-primary"><?php echo $total_barang ?? '1,248'; ?></div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-box me-1"></i> Semua Kategori
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number text-info"><?php echo $total_tube ?? '856'; ?></div>
                <div class="stat-label">Total Tube</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-cog me-1"></i> Stok Tersedia
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number text-success"><?php echo $total_tire ?? '392'; ?></div>
                <div class="stat-label">Total Tire</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-tire me-1"></i> Stok Tersedia
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number text-warning"><?php echo $stok_minimum ?? '12'; ?></div>
                <div class="stat-label">Perlu Restock</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-exclamation-triangle me-1"></i> Stok Minimum
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h4 class="section-title">Grafik Stok Barang</h4>
                <div id="stockChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Detail Stok Barang
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="stockTable">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok Awal</th>
                                    <th>Barang Masuk</th>
                                    <th>Barang Keluar</th>
                                    <th>Stok Akhir</th>
                                    <th>Stok Minimum</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Sample data
                                $stok_list = [
                                    [
                                        'kode' => 'TUB001',
                                        'nama' => 'Tube Standard 17"',
                                        'kategori' => 'Tube',
                                        'stok_awal' => 300,
                                        'masuk' => 50,
                                        'keluar' => 0,
                                        'stok_akhir' => 350,
                                        'stok_minimum' => 50,
                                        'status' => 'aman'
                                    ],
                                    [
                                        'kode' => 'TIR001',
                                        'nama' => 'Tire Radial 205/55/R16',
                                        'kategori' => 'Tire',
                                        'stok_awal' => 120,
                                        'masuk' => 30,
                                        'keluar' => 0,
                                        'stok_akhir' => 150,
                                        'stok_minimum' => 20,
                                        'status' => 'aman'
                                    ],
                                    [
                                        'kode' => 'TUB002',
                                        'nama' => 'Tube Heavy Duty 19"',
                                        'kategori' => 'Tube',
                                        'stok_awal' => 20,
                                        'masuk' => 0,
                                        'keluar' => 12,
                                        'stok_akhir' => 8,
                                        'stok_minimum' => 15,
                                        'status' => 'minimum'
                                    ],
                                    [
                                        'kode' => 'TIR002',
                                        'nama' => 'Tire Offroad 265/70/R16',
                                        'kategori' => 'Tire',
                                        'stok_awal' => 30,
                                        'masuk' => 0,
                                        'keluar' => 5,
                                        'stok_akhir' => 25,
                                        'stok_minimum' => 5,
                                        'status' => 'aman'
                                    ],
                                    [
                                        'kode' => 'TUB003',
                                        'nama' => 'Tube Racing 15"',
                                        'kategori' => 'Tube',
                                        'stok_awal' => 50,
                                        'masuk' => 0,
                                        'keluar' => 5,
                                        'stok_akhir' => 45,
                                        'stok_minimum' => 10,
                                        'status' => 'aman'
                                    ]
                                ];
                                ?>

                                <?php if(!empty($stok_list)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach($stok_list as $stok): ?>
                                        <tr data-kategori="<?php echo $stok['kategori']; ?>" data-status="<?php echo $stok['status']; ?>">
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <span class="badge bg-dark"><?php echo $stok['kode']; ?></span>
                                            </td>
                                            <td><?php echo $stok['nama']; ?></td>
                                            <td>
                                                <span class="badge <?php echo $stok['kategori'] == 'Tube' ? 'bg-tube' : 'bg-tire'; ?>">
                                                    <?php echo $stok['kategori']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $stok['stok_awal']; ?></td>
                                            <td>
                                                <span class="text-success fw-bold">+<?php echo $stok['masuk']; ?></span>
                                            </td>
                                            <td>
                                                <span class="text-danger fw-bold">-<?php echo $stok['keluar']; ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-bold <?php echo $stok['status'] == 'aman' ? 'text-success' : ($stok['status'] == 'minimum' ? 'text-warning' : 'text-danger'); ?>">
                                                    <?php echo $stok['stok_akhir']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $stok['stok_minimum']; ?></td>
                                            <td>
                                                <?php if($stok['status'] == 'aman'): ?>
                                                    <span class="badge bg-success">Aman</span>
                                                <?php elseif($stok['status'] == 'minimum'): ?>
                                                    <span class="badge bg-warning">Minimum</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Habis</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                                <p>Tidak ada data stok</p>
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
                            <button class="btn btn-kenda-red" onclick="exportStockReport()">
                                <i class="fas fa-file-pdf me-2"></i>Export Laporan
                            </button>
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

<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script>
let stockChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeStockChart();
    
    // Apply filters on page load
    applyFilters();
});

function initializeStockChart() {
    stockChart = echarts.init(document.getElementById('stockChart'));
    
    const option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: ['Stok Akhir', 'Stok Minimum'],
            textStyle: {
                color: '#333'
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: ['Tube Standard', 'Tube Heavy', 'Tube Racing', 'Tire Radial', 'Tire Offroad'],
            axisLine: {
                lineStyle: {
                    color: '#666'
                }
            },
            axisLabel: {
                color: '#333',
                rotate: 45
            }
        },
        yAxis: {
            type: 'value',
            axisLine: {
                lineStyle: {
                    color: '#666'
                }
            },
            axisLabel: {
                color: '#333',
                formatter: '{value} unit'
            }
        },
        series: [
            {
                name: 'Stok Akhir',
                type: 'bar',
                data: [350, 8, 45, 150, 25],
                itemStyle: {
                    color: function(params) {
                        const colors = ['#007bff', '#007bff', '#007bff', '#28a745', '#28a745'];
                        return colors[params.dataIndex];
                    }
                }
            },
            {
                name: 'Stok Minimum',
                type: 'line',
                data: [50, 15, 10, 20, 5],
                symbol: 'none',
                lineStyle: {
                    color: '#dc3545',
                    type: 'dashed',
                    width: 2
                }
            }
        ]
    };
    
    stockChart.setOption(option);
}

function applyFilters() {
    const kategori = document.getElementById('filterKategori').value;
    const status = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#stockTable tbody tr');
    
    let visibleCount = 0;
    
    rows.forEach(row => {
        const rowKategori = row.getAttribute('data-kategori');
        const rowStatus = row.getAttribute('data-status');
        
        let showRow = true;
        
        // Apply kategori filter
        if (kategori !== 'all' && rowKategori !== kategori) {
            showRow = false;
        }
        
        // Apply status filter
        if (status !== 'all' && rowStatus !== status) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
        if (showRow) visibleCount++;
    });
    
    // Update chart based on filters
    updateChartData(kategori);
}

function updateChartData(kategori) {
    // In a real application, you would fetch new data from the server
    // For demo, we'll just show a message
    console.log('Updating chart for kategori:', kategori);
}

function exportStockReport() {
    // Implementation for export stock report
    alert('Fitur export laporan stok akan diimplementasikan');
    // You can implement export to PDF/Excel functionality
}

// Handle window resize
window.addEventListener('resize', function() {
    if (stockChart) {
        stockChart.resize();
    }
});
</script>