<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Ambil data dari controller
$stats = isset($dashboard_stats) ? $dashboard_stats : [];
$activities = isset($recent_activities) ? $recent_activities : [];
$packing_lists = isset($recent_packing) ? $recent_packing : [];
$monthly_stats = isset($monthly_stats) ? $monthly_stats : [];
$tube_stats = isset($tube_stats) ? $tube_stats : [];
$tire_stats = isset($tire_stats) ? $tire_stats : [];
$comparison_data = isset($stok_comparison) ? $stok_comparison : [];

// Data untuk grafik
$tube_data = $tube_stats['items'] ?? [];
$tire_data = $tire_stats['items'] ?? [];
?>

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-content">
        <h1>Dashboard Gudang</h1>
        <h3>Selamat Datang, <?php echo isset($username) ? $username : 'admin'; ?>!</h3>
        <p class="mb-0">Sistem Informasi Gudang KENDA - Designed for Your Journey</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($stats['total_barang']) ? number_format($stats['total_barang']) : '0'; ?></div>
        <div class="stat-label">Total Barang</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($stats['total_tube']) ? number_format($stats['total_tube']) : '0'; ?></div>
        <div class="stat-label">Total Tube</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($stats['total_tire']) ? number_format($stats['total_tire']) : '0'; ?></div>
        <div class="stat-label">Total Tire</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($stats['packing_pending']) ? number_format($stats['packing_pending']) : '0'; ?></div>
        <div class="stat-label">Packing Pending</div>
        <span class="stat-trend trend-down">Perlu diproses</span>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <h4 class="section-title">Quick Actions</h4>
            <div class="row">
                <div class="col-md-3 col-6 mb-3 d-flex">
                    <a href="<?php echo site_url('packing_list'); ?>" class="btn btn-kenda w-100 d-flex align-items-center justify-content-center" style="min-height: 50px;">
                        <i class="fas fa-clipboard-list me-2"></i>Buat Packing
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3 d-flex">
                    <a href="<?php echo site_url('scan'); ?>" class="btn btn-kenda-red w-100 d-flex align-items-center justify-content-center" style="min-height: 50px;">
                        <i class="fas fa-qrcode me-2"></i>Scan Label
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3 d-flex">
                    <a href="<?php echo site_url('stok'); ?>" class="btn btn-kenda-red w-100 d-flex align-items-center justify-content-center" style="min-height: 50px;">
                        <i class="fas fa-chart-bar me-2"></i>Laporan Stok
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3 d-flex">
                    <a href="<?php echo site_url('barang'); ?>" class="btn btn-kenda w-100 d-flex align-items-center justify-content-center" style="min-height: 50px;">
                        <i class="fas fa-boxes me-2"></i>Data Barang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Activities -->
<div class="row">
    <!-- Grafik Tube -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="chart-card">
            <h4 class="section-title">
                <i class="fas fa-cog text-primary me-2"></i>Grafik Stok Tube (Top 5)
            </h4>
            <div id="tubeChart" style="height: 300px;"></div>
        </div>
    </div>

    <!-- Grafik Tire -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="chart-card">
            <h4 class="section-title">
                <i class="fas fa-tire text-success me-2"></i>Grafik Stok Tire (Top 5)
            </h4>
            <div id="tireChart" style="height: 300px;"></div>
        </div>
    </div>
</div>

<!-- Perbandingan Stok -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <h4 class="section-title">
                <i class="fas fa-chart-pie me-2"></i>Perbandingan Stok Tube vs Tire
            </h4>
            <div id="comparisonChart" style="height: 350px;"></div>
        </div>
    </div>
</div>

<!-- Activities and Packing List -->
<div class="row">
    <!-- Aktivitas Terbaru -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="activity-card">
            <h4 class="section-title">Aktivitas Terbaru</h4>
            <div class="activity-list">
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-time"><?php echo $activity['time']; ?></div>
                            <div class="activity-content"><?php echo $activity['content']; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                        <p class="text-muted">Tidak ada aktivitas terbaru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Packing List Status -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="chart-card">
            <h4 class="section-title">Status Packing List Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Packing</th>
                            <th>Tanggal</th>
                            <th>Jumlah Item</th>
                            <th>Status Label</th>
                            <th>Status Loading</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($packing_lists)): ?>
                            <?php foreach ($packing_lists as $item): ?>
                                <tr>
                                    <td><?php echo $item['no_packing']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($item['tanggal'])); ?></td>
                                    <td>
                                        <?php if ($item['tube_count'] > 0): ?>
                                            <span class="badge bg-primary me-1"><?php echo $item['tube_count']; ?> Tube</span>
                                        <?php endif; ?>
                                        <?php if ($item['tire_count'] > 0): ?>
                                            <span class="badge bg-success"><?php echo $item['tire_count']; ?> Tire</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="scan-status scan-<?php echo ($item['label_status'] == 'Tercetak' || $item['label_status'] == 'Discan') ? 'completed' : 'pending'; ?>">
                                            <?php echo $item['label_status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="scan-status scan-<?php echo strtolower($item['loading_status']); ?>">
                                            <?php echo $item['loading_status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="printPacking('<?php echo $item['id']; ?>')"
                                                    data-bs-toggle="tooltip" title="Cetak Label">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="viewPacking('<?php echo $item['id']; ?>')"
                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($item['label_status'] == 'Tercetak'): ?>
                                            <button type="button" class="btn btn-outline-warning" 
                                                    onclick="scanPacking('<?php echo $item['id']; ?>')"
                                                    data-bs-toggle="tooltip" title="Scan Label">
                                                <i class="fas fa-qrcode"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <i class="fas fa-box-open text-muted fa-2x mb-2"></i>
                                    <p class="text-muted">Tidak ada data packing list</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail Packing -->
<div class="modal fade" id="detailPackingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Packing List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailPackingContent">
                <!-- Content akan diisi via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printSelectedPacking()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

<script>
// Data untuk grafik dari PHP
const tubeData = <?php echo json_encode($tube_data); ?>;
const tireData = <?php echo json_encode($tire_data); ?>;
const comparisonData = <?php echo json_encode($comparison_data); ?>;
const monthlyStats = <?php echo json_encode($monthly_stats); ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Grafik Tube
    if (tubeData.length > 0) {
        const tubeChart = echarts.init(document.getElementById('tubeChart'));
        const tubeOption = {
            tooltip: {
                trigger: 'item',
                formatter: '{b}: {c} unit'
            },
            legend: {
                orient: 'vertical',
                right: 10,
                top: 'center'
            },
            series: [
                {
                    name: 'Stok Tube',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    center: ['40%', '50%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '14',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: tubeData.map(item => ({
                        name: item.nama_barang.length > 20 ? item.nama_barang.substring(0, 20) + '...' : item.nama_barang,
                        value: item.stok,
                        itemStyle: { color: getTubeColor(item.nama_barang) }
                    }))
                }
            ]
        };
        tubeChart.setOption(tubeOption);
    } else {
        document.getElementById('tubeChart').innerHTML = '<div class="text-center py-4"><i class="fas fa-chart-pie fa-3x text-muted mb-3"></i><p>Tidak ada data Tube</p></div>';
    }
    
    // Grafik Tire
    if (tireData.length > 0) {
        const tireChart = echarts.init(document.getElementById('tireChart'));
        const tireOption = {
            tooltip: {
                trigger: 'item',
                formatter: '{b}: {c} unit'
            },
            legend: {
                orient: 'vertical',
                right: 10,
                top: 'center'
            },
            series: [
                {
                    name: 'Stok Tire',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    center: ['40%', '50%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '14',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: tireData.map(item => ({
                        name: item.nama_barang.length > 20 ? item.nama_barang.substring(0, 20) + '...' : item.nama_barang,
                        value: item.stok,
                        itemStyle: { color: getTireColor(item.nama_barang) }
                    }))
                }
            ]
        };
        tireChart.setOption(tireOption);
    } else {
        document.getElementById('tireChart').innerHTML = '<div class="text-center py-4"><i class="fas fa-chart-pie fa-3x text-muted mb-3"></i><p>Tidak ada data Tire</p></div>';
    }
    
    // Grafik Perbandingan
    const comparisonChart = echarts.init(document.getElementById('comparisonChart'));
    
    // Hitung data untuk perbandingan
    const tubeStokSaatIni = tubeData.reduce((sum, item) => sum + parseInt(item.stok), 0);
    const tireStokSaatIni = tireData.reduce((sum, item) => sum + parseInt(item.stok), 0);
    
    const tubeStokMinimum = tubeData.reduce((sum, item) => sum + parseInt(item.stok_minimum), 0);
    const tireStokMinimum = tireData.reduce((sum, item) => sum + parseInt(item.stok_minimum), 0);
    
    const comparisonOption = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: ['Tube', 'Tire'],
            top: 10
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: ['Stok Saat Ini', 'Stok Minimum', 'Packing Bulan Ini', 'Keluar Bulan Ini', 'Loading Bulan Ini']
        },
        yAxis: {
            type: 'value',
            name: 'Jumlah Unit'
        },
        series: [
            {
                name: 'Tube',
                type: 'bar',
                data: [
                    tubeStokSaatIni,
                    tubeStokMinimum,
                    monthlyStats.packing_this_month || 0,
                    monthlyStats.keluar_this_month || 0,
                    monthlyStats.loading_this_month || 0
                ],
                itemStyle: {
                    color: '#007bff'
                },
                emphasis: {
                    focus: 'series'
                }
            },
            {
                name: 'Tire',
                type: 'bar',
                data: [
                    tireStokSaatIni,
                    tireStokMinimum,
                    monthlyStats.packing_this_month || 0,
                    monthlyStats.keluar_this_month || 0,
                    monthlyStats.loading_this_month || 0
                ],
                itemStyle: {
                    color: '#28a745'
                },
                emphasis: {
                    focus: 'series'
                }
            }
        ]
    };
    
    comparisonChart.setOption(comparisonOption);
    
    // Handle resize
    window.addEventListener('resize', function() {
        if (tubeData.length > 0) tubeChart.resize();
        if (tireData.length > 0) tireChart.resize();
        comparisonChart.resize();
    });
});

// Fungsi untuk mendapatkan warna Tube
function getTubeColor(name) {
    const colors = [
        '#007bff', '#0056b3', '#66b3ff', '#3399ff', '#99ccff',
        '#1e90ff', '#4169e1', '#6495ed', '#87ceeb', '#b0c4de'
    ];
    // Gunakan hash dari nama untuk mendapatkan warna konsisten
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    return colors[Math.abs(hash) % colors.length];
}

// Fungsi untuk mendapatkan warna Tire
function getTireColor(name) {
    const colors = [
        '#28a745', '#1e7e34', '#4caf50', '#388e3c', '#81c784',
        '#2e8b57', '#3cb371', '#66cdaa', '#90ee90', '#98fb98'
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    return colors[Math.abs(hash) % colors.length];
}

// Fungsi untuk melihat detail packing
let currentPackingId = null;

function viewPacking(id) {
    currentPackingId = id;
    
    fetch(`<?php echo site_url('packing-list/api/detail/'); ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const packing = data.data;
                let itemsHtml = '';
                
                if (packing.items && packing.items.length > 0) {
                    itemsHtml = `
                    <h6>Detail Items:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${packing.items.map(item => `
                                    <tr>
                                        <td>${item.kode_barang}</td>
                                        <td>${item.nama_barang}</td>
                                        <td><span class="badge ${item.kategori === 'Tube' ? 'bg-primary' : 'bg-success'}">${item.kategori}</span></td>
                                        <td>${item.qty}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    `;
                }
                
                const modalContent = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>No. Packing:</strong> ${packing.no_packing}</p>
                            <p><strong>Tanggal:</strong> ${packing.tanggal}</p>
                            <p><strong>Customer:</strong> ${packing.customer}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Alamat:</strong> ${packing.alamat}</p>
                            <p><strong>Status Label:</strong> ${packing.status_scan_out}</p>
                            <p><strong>Status Loading:</strong> ${packing.status_scan_in}</p>
                        </div>
                    </div>
                    ${itemsHtml}
                `;
                
                document.getElementById('detailPackingContent').innerHTML = modalContent;
                
                const modal = new bootstrap.Modal(document.getElementById('detailPackingModal'));
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat memuat data'
            });
        });
}

// Fungsi untuk print packing
function printPacking(id) {
    // Arahkan ke halaman cetak atau buka modal cetak
    window.open(`<?php echo site_url('packing_list/cetak/'); ?>${id}`, '_blank');
}

// Fungsi untuk scan packing
function scanPacking(id) {
    // Tampilkan modal scan atau redirect ke halaman scan
    window.location.href = `<?php echo site_url('scan'); ?>?packing_id=${id}`;
}

// Fungsi untuk print dari modal
function printSelectedPacking() {
    if (currentPackingId) {
        printPacking(currentPackingId);
        const modal = bootstrap.Modal.getInstance(document.getElementById('detailPackingModal'));
        modal.hide();
    }
}
</script>

<style>
/* Tambahan CSS untuk kesamaan tinggi button */
.btn-kenda, .btn-kenda-red {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 50px;
    padding: 0.5rem 1rem;
    font-size: 0.95rem;
}

/* Pastikan semua button di quick action sama tinggi */
.quick-actions .row > div {
    display: flex;
}

/* Activity list styles */
.activity-item {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-time {
    min-width: 60px;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
}

.activity-content {
    flex: 1;
    font-size: 0.95rem;
    line-height: 1.4;
}

/* Status badges */
.scan-status {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.scan-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.scan-completed {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.scan-loading {
    background-color: #cce5ff;
    color: #004085;
    border: 1px solid #b8daff;
}

.scan-selesai {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

/* Tooltip custom */
.tooltip {
    font-size: 0.85rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-kenda, .btn-kenda-red {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
        min-height: 45px;
    }
    
    .activity-item {
        flex-direction: column;
    }
    
    .activity-time {
        margin-bottom: 0.25rem;
    }
}
</style>
