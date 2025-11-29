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
        <div class="stat-number">1,248</div>
        <div class="stat-label">Total Barang</div>
        <span class="stat-trend trend-up">+125 dari target</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number">56</div>
        <div class="stat-label">Barang Masuk</div>
        <span class="stat-trend trend-up">Hari ini</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number">42</div>
        <div class="stat-label">Barang Keluar</div>
        <span class="stat-trend trend-up">Hari ini</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-number">12</div>
        <div class="stat-label">Stok Minimum</div>
        <span class="stat-trend trend-down">Perlu restock</span>
    </div>
</div>

<!-- Charts and Activities -->
<div class="charts-container">
    <!-- Grafik Stok Barang dengan ECharts -->
    <div class="chart-card">
        <h4 class="section-title">Grafik Stok Barang</h4>
        <div id="stokChart" style="height: 300px;"></div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="activity-card">
        <h4 class="section-title">Aktivitas Terbaru</h4>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-time">10:30</div>
                <div class="activity-content">
                    <strong>Barang A masuk</strong> - 50 unit
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-time">09:15</div>
                <div class="activity-content">
                    <strong>Barang B keluar</strong> - 30 unit
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-time">08:45</div>
                <div class="activity-content">
                    <strong>Stok Barang C diperbarui</strong>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-time">08:30</div>
                <div class="activity-content">
                    <strong>User admin login sistem</strong>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-time">07:15</div>
                <div class="activity-content">
                    <strong>Barang D masuk</strong> - 25 unit
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Charts Section -->
<div class="row mt-4">
    <div class="col-12 col-md-6 mb-4">
        <div class="chart-card">
            <h4 class="section-title">Trend Stok Bulanan</h4>
            <div id="trendChart" style="height: 250px;"></div>
        </div>
    </div>
    <div class="col-12 col-md-6 mb-4">
        <div class="chart-card">
            <h4 class="section-title">Kategori Barang</h4>
            <div id="kategoriChart" style="height: 250px;"></div>
        </div>
    </div>
</div>

<!-- Additional Info Section -->
<div class="row mt-3">
    <div class="col-12 col-md-6 mb-3">
        <div class="chart-card h-100">
            <h4 class="section-title">Status Inventory</h4>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="small">Barang Tipe A</span>
                <span class="badge bg-success">Stok Aman</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="small">Barang Tipe B</span>
                <span class="badge bg-warning">Stok Menipis</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="small">Barang Tipe C</span>
                <span class="badge bg-danger">Perlu Restock</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <div class="chart-card h-100">
            <h4 class="section-title">Quick Actions</h4>
            <div class="d-grid gap-2">
                <button class="btn btn-kenda btn-sm">
                    <i class="fas fa-plus me-1"></i>Tambah Barang
                </button>
                <button class="btn btn-kenda-red btn-sm">
                    <i class="fas fa-file-pdf me-1"></i>Generate Laporan
                </button>
                <button class="btn btn-kenda btn-sm">
                    <i class="fas fa-exclamation-triangle me-1"></i>Cek Stok Minimum
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Load ECharts Library -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script>
// Initialize charts after page load
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Stok Barang (Bar Chart)
    const stokChart = echarts.init(document.getElementById('stokChart'));
    const stokOption = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            },
            formatter: function(params) {
                return params[0].name + '<br/>' + 
                       params[0].marker + params[0].seriesName + ': ' + 
                       params[0].value + ' unit';
            }
        },
        legend: {
            data: ['Stok Saat Ini', 'Stok Minimum'],
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
            data: ['Barang A', 'Barang B', 'Barang C', 'Barang D', 'Barang E', 'Barang F'],
            axisLine: {
                lineStyle: {
                    color: '#666'
                }
            },
            axisLabel: {
                color: '#333'
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
                name: 'Stok Saat Ini',
                type: 'bar',
                data: [150, 230, 224, 218, 135, 147],
                itemStyle: {
                    color: '#ff0000'
                },
                emphasis: {
                    itemStyle: {
                        color: '#cc0000'
                    }
                }
            },
            {
                name: 'Stok Minimum',
                type: 'line',
                data: [50, 50, 50, 50, 50, 50],
                symbol: 'none',
                lineStyle: {
                    color: '#666',
                    type: 'dashed',
                    width: 2
                }
            }
        ]
    };
    stokChart.setOption(stokOption);

    // Trend Stok Bulanan (Line Chart)
    const trendChart = echarts.init(document.getElementById('trendChart'));
    const trendOption = {
        tooltip: {
            trigger: 'axis',
            formatter: function(params) {
                let result = params[0].axisValue + '<br/>';
                params.forEach(function(item) {
                    result += item.marker + item.seriesName + ': ' + item.value + ' unit<br/>';
                });
                return result;
            }
        },
        legend: {
            data: ['Barang Masuk', 'Barang Keluar'],
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
            boundaryGap: false,
            data: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            axisLine: {
                lineStyle: {
                    color: '#666'
                }
            },
            axisLabel: {
                color: '#333'
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
                name: 'Barang Masuk',
                type: 'line',
                data: [120, 132, 101, 134, 90, 230, 210],
                smooth: true,
                lineStyle: {
                    color: '#28a745',
                    width: 3
                },
                itemStyle: {
                    color: '#28a745'
                }
            },
            {
                name: 'Barang Keluar',
                type: 'line',
                data: [85, 93, 90, 93, 129, 133, 140],
                smooth: true,
                lineStyle: {
                    color: '#ff0000',
                    width: 3
                },
                itemStyle: {
                    color: '#ff0000'
                }
            }
        ]
    };
    trendChart.setOption(trendOption);

    // Kategori Barang (Pie Chart)
    const kategoriChart = echarts.init(document.getElementById('kategoriChart'));
    const kategoriOption = {
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} unit ({d}%)'
        },
        legend: {
            orient: 'vertical',
            right: 10,
            top: 'center',
            textStyle: {
                color: '#333'
            }
        },
        series: [
            {
                name: 'Kategori Barang',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                itemStyle: {
                    borderRadius: 10,
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
                data: [
                    { value: 335, name: 'Ball Barang', itemStyle: { color: '#ff0000' } },
                    { value: 310, name: 'Roller Barang', itemStyle: { color: '#dc3545' } },
                    { value: 234, name: 'Tapered Barang', itemStyle: { color: '#c82333' } },
                    { value: 135, name: 'Spherical Barang', itemStyle: { color: '#a71e2a' } },
                    { value: 154, name: 'Lainnya', itemStyle: { color: '#800000' } }
                ]
            }
        ]
    };
    kategoriChart.setOption(kategoriOption);

    // Handle window resize for all charts
    window.addEventListener('resize', function() {
        stokChart.resize();
        trendChart.resize();
        kategoriChart.resize();
    });

    // Handle sidebar toggle for chart resizing
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            setTimeout(function() {
                stokChart.resize();
                trendChart.resize();
                kategoriChart.resize();
            }, 300);
        });
    }

    // Handle sidebar backdrop click for chart resizing
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', function() {
            setTimeout(function() {
                stokChart.resize();
                trendChart.resize();
                kategoriChart.resize();
            }, 300);
        });
    }
});
</script>

<style>
/* Additional styles for ECharts */
.echarts-for-react, .echarts {
    width: 100% !important;
}

/* Chart container responsive */
#stokChart, #trendChart, #kategoriChart {
    width: 100%;
}

/* Mobile adjustments for charts */
@media (max-width: 768px) {
    #stokChart, #trendChart, #kategoriChart {
        height: 250px !important;
    }
    
    .charts-container {
        gap: 15px;
    }
}

@media (max-width: 480px) {
    #stokChart, #trendChart, #kategoriChart {
        height: 200px !important;
    }
}
</style>
