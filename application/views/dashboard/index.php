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
        <div class="stat-number"><?php echo isset($total_barang) ? number_format($total_barang) : '0'; ?></div>
        <div class="stat-label">Total Barang</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($total_tube) ? number_format($total_tube) : '0'; ?></div>
        <div class="stat-label">Total Tube</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($total_tire) ? number_format($total_tire) : '0'; ?></div>
        <div class="stat-label">Total Tire</div>
        <span class="stat-trend trend-up">Stok tersedia</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo isset($packing_pending) ? number_format($packing_pending) : '0'; ?></div>
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
                <?php
                // In a real application, you would fetch this from the database
                // For demo purposes, we'll use static data
                $activities = [
                    ['time' => '10:30', 'content' => '<strong>Packing List #PL001 dibuat</strong> - 50 unit Tire'],
                    ['time' => '09:15', 'content' => '<strong>Label #LBL002 discan keluar</strong> - 30 unit Tube'],
                    ['time' => '08:45', 'content' => '<strong>Barang masuk dari Supplier A</strong> - 100 unit Tire'],
                    ['time' => '08:30', 'content' => '<strong>Label #LBL001 loading completed</strong>'],
                    ['time' => '07:15', 'content' => '<strong>Stok Tube diperbarui</strong> - Saldo: ' . ($total_tube ?? 0) . ' unit']
                ];
                
                foreach ($activities as $activity) {
                    echo '<div class="activity-item">';
                    echo '    <div class="activity-time">' . $activity['time'] . '</div>';
                    echo '    <div class="activity-content">' . $activity['content'] . '</div>';
                    echo '</div>';
                }
                ?>
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
                        <?php
                        // In a real application, you would fetch this from the database
                        $packing_items = [
                            ['no' => 'PL001', 'date' => '2024-03-20', 'tube' => 15, 'tire' => 35, 'label_status' => 'Tercetak', 'loading_status' => 'Selesai'],
                            ['no' => 'PL002', 'date' => '2024-03-20', 'tube' => 30, 'tire' => 0, 'label_status' => 'Tercetak', 'loading_status' => 'Pending'],
                            ['no' => 'PL003', 'date' => '2024-03-19', 'tube' => 0, 'tire' => 25, 'label_status' => 'Belum', 'loading_status' => 'Pending'],
                            ['no' => 'PL004', 'date' => '2024-03-19', 'tube' => 20, 'tire' => 10, 'label_status' => 'Tercetak', 'loading_status' => 'Loading']
                        ];
                        
                        foreach ($packing_items as $item) {
                            echo '<tr>';
                            echo '    <td>' . $item['no'] . '</td>';
                            echo '    <td>' . $item['date'] . '</td>';
                            echo '    <td>';
                            if ($item['tube'] > 0) {
                                echo '<span class="badge bg-primary me-1">' . $item['tube'] . ' Tube</span>';
                            }
                            if ($item['tire'] > 0) {
                                echo '<span class="badge bg-success">' . $item['tire'] . ' Tire</span>';
                            }
                            echo '    </td>';
                            echo '    <td><span class="scan-status scan-' . ($item['label_status'] == 'Tercetak' ? 'completed' : 'pending') . '">' . $item['label_status'] . '</span></td>';
                            echo '    <td><span class="scan-status scan-' . strtolower($item['loading_status']) . '">' . $item['loading_status'] . '</span></td>';
                            echo '    <td>';
                            echo '        <button class="btn btn-sm btn-outline-primary">';
                            echo '            <i class="fas fa-print"></i>';
                            echo '        </button>';
                            echo '    </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari PHP
    const totalTube = <?php echo isset($total_tube) ? $total_tube : 0; ?>;
    const totalTire = <?php echo isset($total_tire) ? $total_tire : 0; ?>;
    
    // Data contoh untuk grafik (dalam aplikasi nyata, ini akan diambil dari database)
    const tubeData = [
        { name: 'Tube Standard 17"', value: Math.round(totalTube * 0.4) },
        { name: 'Tube Heavy Duty 19"', value: Math.round(totalTube * 0.27) },
        { name: 'Tube Racing 15"', value: Math.round(totalTube * 0.17) },
        { name: 'Tube Truck 22"', value: Math.round(totalTube * 0.11) },
        { name: 'Tube Motor 14"', value: Math.round(totalTube * 0.05) }
    ];

    const tireData = [
        { name: 'Tire Radial 205/55/R16', value: Math.round(totalTire * 0.35) },
        { name: 'Tire Offroad 265/70/R16', value: Math.round(totalTire * 0.25) },
        { name: 'Tire Sport 225/45/R17', value: Math.round(totalTire * 0.20) },
        { name: 'Tire Truck 275/70/R22', value: Math.round(totalTire * 0.15) },
        { name: 'Tire ECO 185/65/R15', value: Math.round(totalTire * 0.05) }
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
                data: [totalTube, Math.round(totalTube * 0.12), Math.round(totalTube * 0.29), Math.round(totalTube * 0.21)],
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
                data: [totalTire, Math.round(totalTire * 0.13), Math.round(totalTire * 0.48), Math.round(totalTire * 0.31)],
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
