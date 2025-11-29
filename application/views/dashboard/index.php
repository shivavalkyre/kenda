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
        <div class="stat-number"><?php echo $total_barang ?? '1,248'; ?></div>
        <div class="stat-label">Total Barang</div>
        <span class="stat-trend trend-up">+125 dari target</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo $total_tube ?? '856'; ?></div>
        <div class="stat-label">Total Tube</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo $total_tire ?? '392'; ?></div>
        <div class="stat-label">Total Tire</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo $packing_pending ?? '8'; ?></div>
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
                <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo site_url('packing_list'); ?>" class="btn btn-kenda w-100">
                        <i class="fas fa-clipboard-list me-2"></i>Buat Packing
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo site_url('scan'); ?>" class="btn btn-kenda-red w-100">
                        <i class="fas fa-qrcode me-2"></i>Scan Label
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo site_url('stok'); ?>" class="btn btn-kenda-red w-100">
                        <i class="fas fa-chart-bar me-2"></i>Laporan Stok
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo site_url('barang'); ?>" class="btn btn-kenda w-100">
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
                <i class="fas fa-cog text-primary me-2"></i>Grafik Stok Tube
            </h4>
            <div id="tubeChart" style="height: 300px;"></div>
        </div>
    </div>

    <!-- Grafik Tire -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="chart-card">
            <h4 class="section-title">
                <i class="fas fa-tire text-success me-2"></i>Grafik Stok Tire
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
                <div class="activity-item">
                    <div class="activity-time">10:30</div>
                    <div class="activity-content">
                        <strong>Packing List #PL001 dibuat</strong> - 50 unit Tire
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-time">09:15</div>
                    <div class="activity-content">
                        <strong>Label #LBL002 discan keluar</strong> - 30 unit Tube
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-time">08:45</div>
                    <div class="activity-content">
                        <strong>Barang masuk dari Supplier A</strong> - 100 unit Tire
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-time">08:30</div>
                    <div class="activity-content">
                        <strong>Label #LBL001 loading completed</strong>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-time">07:15</div>
                    <div class="activity-content">
                        <strong>Stok Tube diperbarui</strong> - Saldo: 856 unit
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Packing List Status -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="chart-card">
            <h4 class="section-title">Status Packing List</h4>
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
                        <tr>
                            <td>PL001</td>
                            <td>2024-03-20</td>
                            <td>
                                <span class="badge bg-primary me-1">15 Tube</span>
                                <span class="badge bg-success">35 Tire</span>
                            </td>
                            <td><span class="scan-status scan-completed">Tercetak</span></td>
                            <td><span class="scan-status scan-loaded">Selesai</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>PL002</td>
                            <td>2024-03-20</td>
                            <td>
                                <span class="badge bg-primary">30 Tube</span>
                            </td>
                            <td><span class="scan-status scan-completed">Tercetak</span></td>
                            <td><span class="scan-status scan-pending">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>PL003</td>
                            <td>2024-03-19</td>
                            <td>
                                <span class="badge bg-success">25 Tire</span>
                            </td>
                            <td><span class="scan-status scan-pending">Belum</span></td>
                            <td><span class="scan-status scan-pending">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>PL004</td>
                            <td>2024-03-19</td>
                            <td>
                                <span class="badge bg-primary me-1">20 Tube</span>
                                <span class="badge bg-success">10 Tire</span>
                            </td>
                            <td><span class="scan-status scan-completed">Tercetak</span></td>
                            <td><span class="scan-status scan-completed">Loading</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-shipping-fast"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data contoh untuk grafik
    const tubeData = [
        { name: 'Tube Standard 17"', value: 350 },
        { name: 'Tube Heavy Duty 19"', value: 230 },
        { name: 'Tube Racing 15"', value: 147 },
        { name: 'Tube Truck 22"', value: 98 },
        { name: 'Tube Motor 14"', value: 31 }
    ];

    const tireData = [
        { name: 'Tire Radial 205/55/R16', value: 224 },
        { name: 'Tire Offroad 265/70/R16', value: 135 },
        { name: 'Tire Sport 225/45/R17', value: 87 },
        { name: 'Tire Truck 275/70/R22', value: 56 },
        { name: 'Tire ECO 185/65/R15', value: 42 }
    ];

    // Grafik Tube
    const tubeChart = echarts.init(document.getElementById('tubeChart'));
    const tubeOption = {
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} unit ({d}%)'
        },
        legend: {
            orient: 'vertical',
            right: 10,
            top: 'center',
            data: tubeData.map(item => item.name)
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
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: tubeData.map(item => ({
                    ...item,
                    itemStyle: { color: getTubeColor(item.name) }
                }))
            }
        ]
    };
    tubeChart.setOption(tubeOption);

    // Grafik Tire
    const tireChart = echarts.init(document.getElementById('tireChart'));
    const tireOption = {
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} unit ({d}%)'
        },
        legend: {
            orient: 'vertical',
            right: 10,
            top: 'center',
            data: tireData.map(item => item.name)
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
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: tireData.map(item => ({
                    ...item,
                    itemStyle: { color: getTireColor(item.name) }
                }))
            }
        ]
    };
    tireChart.setOption(tireOption);

    // Grafik Perbandingan
    const comparisonChart = echarts.init(document.getElementById('comparisonChart'));
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
            data: ['Stok Saat Ini', 'Stok Minimum', 'Packing Bulan Ini', 'Rata-rata Penjualan']
        },
        yAxis: {
            type: 'value',
            name: 'Jumlah Unit'
        },
        series: [
            {
                name: 'Tube',
                type: 'bar',
                data: [856, 100, 245, 180],
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
                data: [392, 50, 187, 120],
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

    // Fungsi untuk mendapatkan warna Tube
    function getTubeColor(name) {
        const colors = {
            'Tube Standard 17"': '#007bff',
            'Tube Heavy Duty 19"': '#0056b3',
            'Tube Racing 15"': '#66b3ff',
            'Tube Truck 22"': '#3399ff',
            'Tube Motor 14"': '#99ccff'
        };
        return colors[name] || '#007bff';
    }

    // Fungsi untuk mendapatkan warna Tire
    function getTireColor(name) {
        const colors = {
            'Tire Radial 205/55/R16': '#28a745',
            'Tire Offroad 265/70/R16': '#1e7e34',
            'Tire Sport 225/45/R17': '#4caf50',
            'Tire Truck 275/70/R22': '#388e3c',
            'Tire ECO 185/65/R15': '#81c784'
        };
        return colors[name] || '#28a745';
    }

    // Handle resize
    window.addEventListener('resize', function() {
        tubeChart.resize();
        tireChart.resize();
        comparisonChart.resize();
    });
});
</script>

<style>
.echarts-for-react, .echarts { width: 100% !important; }
#tubeChart, #tireChart, #comparisonChart { width: 100%; }
@media (max-width: 768px) { 
    #tubeChart, #tireChart, #comparisonChart { height: 250px !important; } 
}
@media (max-width: 480px) { 
    #tubeChart, #tireChart, #comparisonChart { height: 200px !important; } 
}

.scan-status { padding: 4px 8px; border-radius: 4px; font-size: .8rem; font-weight: 500; }
.scan-pending { background:#fff3cd;color:#856404; }
.scan-completed { background:#d1ecf1;color:#0c5460; }
.scan-loaded { background:#d4edda;color:#155724; }

/* --- FIX: Samakan tinggi semua tombol Quick Actions --- */
.btn-kenda,
.btn-kenda-red {
    height: 55px;                /* tinggi seragam */
    display: flex;               /* agar teks & ikon rata tengah */
    align-items: center;
    justify-content: center;
    font-weight: 600;
}
</style>