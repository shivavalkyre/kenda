<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-qrcode me-2"></i>Scan Label
                </h1>
                <p class="text-muted">Scan label barang untuk proses keluar gudang dan loading</p>
            </div>
        </div>
    </div>

    <!-- Scan Interface -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-card">
                <h4 class="section-title">Scanner</h4>
                
                <!-- Camera Preview -->
                <div id="cameraPreview" class="border rounded mb-3" style="height: 300px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center text-muted">
                        <i class="fas fa-camera fa-3x mb-3"></i>
                        <p>Kamera siap untuk scanning</p>
                        <button class="btn btn-kenda" onclick="startCamera()">
                            <i class="fas fa-play me-2"></i>Mulai Scan
                        </button>
                    </div>
                </div>
                
                <!-- Manual Input -->
                <div class="mb-3">
                    <label for="manualInput" class="form-label">Atau input manual kode label:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="manualInput" placeholder="Masukkan kode label...">
                        <button class="btn btn-kenda" onclick="processManualInput()">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Scan Results -->
                <div id="scanResults" style="display: none;">
                    <h6 class="section-title">Hasil Scan</h6>
                    <div id="resultContent"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <!-- Scan Statistics -->
            <div class="chart-card mb-4">
                <h4 class="section-title">Statistik Hari Ini</h4>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="stat-number text-primary" id="todayScanned">0</div>
                        <div class="stat-label">Terscan</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-number text-success" id="todayLoaded">0</div>
                        <div class="stat-label">Loading</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-number text-info" id="todayCompleted">0</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Scans -->
            <div class="chart-card">
                <h4 class="section-title">Scan Terbaru</h4>
                <div class="activity-list" id="recentScans">
                    <div class="activity-item">
                        <div class="activity-time">10:30</div>
                        <div class="activity-content">
                            <strong>Label #LBL001</strong> - Scan keluar gudang
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">10:25</div>
                        <div class="activity-content">
                            <strong>Label #LBL002</strong> - Loading selesai
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">10:15</div>
                        <div class="activity-content">
                            <strong>Label #LBL003</strong> - Scan keluar gudang
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simulated data
let scannedToday = 8;
let loadedToday = 5;
let completedToday = 3;

let recentScans = [
    { time: '10:30', label: 'LBL001', type: 'out' },
    { time: '10:25', label: 'LBL002', type: 'loading' },
    { time: '10:15', label: 'LBL003', type: 'out' }
];

document.addEventListener('DOMContentLoaded', function() {
    updateStatistics();
    updateRecentScans();
});

function startCamera() {
    // In a real implementation, this would access the device camera
    // For demo purposes, we'll simulate camera access
    document.getElementById('cameraPreview').innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-kenda-red mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Mengakses kamera...</p>
            <button class="btn btn-kenda-red mt-2" onclick="simulateScan()">
                <i class="fas fa-camera me-2"></i>Simulate Scan
            </button>
        </div>
    `;
}

function simulateScan() {
    // Simulate scanning a label
    const labels = ['LBL001', 'LBL002', 'LBL003', 'LBL004', 'LBL005'];
    const randomLabel = labels[Math.floor(Math.random() * labels.length)];
    
    processScanResult(randomLabel);
}

function processManualInput() {
    const input = document.getElementById('manualInput').value.trim();
    if (input === '') {
        alert('Masukkan kode label!');
        return;
    }
    
    processScanResult(input);
    document.getElementById('manualInput').value = '';
}

function processScanResult(labelCode) {
    // Simulate processing scan result
    const resultContent = document.getElementById('resultContent');
    const scanResults = document.getElementById('scanResults');
    
    // Determine scan type based on label status (simulated)
    const scanType = Math.random() > 0.5 ? 'out' : 'loading';
    
    if (scanType === 'out') {
        resultContent.innerHTML = `
            <div class="alert alert-success">
                <h6><i class="fas fa-check-circle me-2"></i>Scan Berhasil!</h6>
                <p><strong>Label:</strong> ${labelCode}</p>
                <p><strong>Status:</strong> Barang keluar gudang tercatat</p>
                <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-kenda" onclick="confirmScan('${labelCode}', 'out')">
                    <i class="fas fa-check me-2"></i>Konfirmasi Scan Keluar
                </button>
            </div>
        `;
    } else {
        resultContent.innerHTML = `
            <div class="alert alert-info">
                <h6><i class="fas fa-truck-loading me-2"></i>Loading Scan!</h6>
                <p><strong>Label:</strong> ${labelCode}</p>
                <p><strong>Status:</strong> Konfirmasi loading barang</p>
                <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-kenda-red" onclick="confirmScan('${labelCode}', 'loading')">
                    <i class="fas fa-truck me-2"></i>Konfirmasi Loading
                </button>
            </div>
        `;
    }
    
    scanResults.style.display = 'block';
    
    // Add to recent scans
    recentScans.unshift({
        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
        label: labelCode,
        type: scanType
    });
    
    // Keep only last 5 scans
    if (recentScans.length > 5) {
        recentScans.pop();
    }
    
    updateRecentScans();
}

function confirmScan(labelCode, type) {
    if (type === 'out') {
        scannedToday++;
        alert(`Label ${labelCode} berhasil dicatat sebagai barang keluar gudang.`);
    } else {
        loadedToday++;
        completedToday++;
        alert(`Label ${labelCode} berhasil dikonfirmasi loading selesai.`);
    }
    
    updateStatistics();
    
    // Reset for next scan
    document.getElementById('scanResults').style.display = 'none';
    document.getElementById('cameraPreview').innerHTML = `
        <div class="text-center text-muted">
            <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
            <p>Scan berhasil diproses</p>
            <button class="btn btn-kenda" onclick="startCamera()">
                <i class="fas fa-redo me-2"></i>Scan Lagi
            </button>
        </div>
    `;
}

function updateStatistics() {
    document.getElementById('todayScanned').textContent = scannedToday;
    document.getElementById('todayLoaded').textContent = loadedToday;
    document.getElementById('todayCompleted').textContent = completedToday;
}

function updateRecentScans() {
    const container = document.getElementById('recentScans');
    container.innerHTML = '';
    
    recentScans.forEach(scan => {
        const icon = scan.type === 'out' ? 'fa-arrow-right' : 'fa-truck-loading';
        const color = scan.type === 'out' ? 'text-primary' : 'text-success';
        
        container.innerHTML += `
            <div class="activity-item">
                <div class="activity-time">${scan.time}</div>
                <div class="activity-content">
                    <strong>Label ${scan.label}</strong> - 
                    ${scan.type === 'out' ? 'Scan keluar gudang' : 'Loading selesai'}
                    <i class="fas ${icon} ${color} ms-1"></i>
                </div>
            </div>
        `;
    });
}

// Handle Enter key in manual input
document.getElementById('manualInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        processManualInput();
    }
});
</script>