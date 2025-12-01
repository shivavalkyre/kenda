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
// Status constants untuk konsistensi
const SCAN_STATUS = {
    DRAFT: 'draft',
    PRINTED: 'printed',
    SCANNED_OUT: 'scanned_out',
    SCANNED_IN: 'scanned_in',
    COMPLETED: 'completed',
    PENDING: 'pending'
};

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
    
    // Validasi format label
    if (!validateLabelFormat(input)) {
        alert('Format label tidak valid! Format yang benar: LBLXXX (contoh: LBL001)');
        return;
    }
    
    processScanResult(input);
    document.getElementById('manualInput').value = '';
}

function validateLabelFormat(label) {
    const labelRegex = /^LBL\d{3,}$/i;
    return labelRegex.test(label);
}

async function processScanResult(labelCode) {
    try {
        // Cek status packing list dari database
        const response = await fetch(`${baseUrl}/packing-list/api/check-label/${labelCode}`);
        const data = await response.json();
        
        if (!data.success) {
            showScanError('Label tidak ditemukan atau tidak valid');
            return;
        }
        
        const packingData = data.data;
        showScanResult(packingData, labelCode);
        
    } catch (error) {
        console.error('Error:', error);
        showScanError('Terjadi kesalahan saat memproses scan');
    }
}

function showScanResult(packingData, labelCode) {
    const resultContent = document.getElementById('resultContent');
    const scanResults = document.getElementById('scanResults');
    
    const { status_scan_out, status_scan_in, no_packing, customer } = packingData;
    
    let scanType = '';
    let alertType = '';
    let message = '';
    let buttonAction = '';
    
    // Tentukan aksi berdasarkan status saat ini
    if (status_scan_out === SCAN_STATUS.PRINTED && status_scan_in === SCAN_STATUS.PENDING) {
        // Bisa scan out
        scanType = 'out';
        alertType = 'success';
        message = `
            <h6><i class="fas fa-check-circle me-2"></i>Scan Out Ditemukan!</h6>
            <p><strong>Packing List:</strong> ${no_packing}</p>
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Status:</strong> Siap untuk scan keluar gudang</p>
            <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
        `;
        buttonAction = `confirmScan('${labelCode}', '${SCAN_STATUS.SCANNED_OUT}', ${packingData.id})`;
        
    } else if (status_scan_out === SCAN_STATUS.SCANNED_OUT && status_scan_in === SCAN_STATUS.PENDING) {
        // Bisa scan in (loading)
        scanType = 'loading';
        alertType = 'info';
        message = `
            <h6><i class="fas fa-truck-loading me-2"></i>Scan Loading Ditemukan!</h6>
            <p><strong>Packing List:</strong> ${no_packing}</p>
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Status:</strong> Siap untuk scan loading kendaraan</p>
            <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
        `;
        buttonAction = `confirmScan('${labelCode}', '${SCAN_STATUS.SCANNED_IN}', ${packingData.id})`;
        
    } else if (status_scan_out === SCAN_STATUS.SCANNED_OUT && status_scan_in === SCAN_STATUS.SCANNED_IN) {
        // Bisa complete loading
        scanType = 'complete';
        alertType = 'warning';
        message = `
            <h6><i class="fas fa-check-double me-2"></i>Loading Siap Diselesaikan!</h6>
            <p><strong>Packing List:</strong> ${no_packing}</p>
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Status:</strong> Siap untuk konfirmasi selesai loading</p>
            <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
        `;
        buttonAction = `confirmScan('${labelCode}', '${SCAN_STATUS.COMPLETED}', ${packingData.id})`;
        
    } else if (status_scan_in === SCAN_STATUS.COMPLETED) {
        // Sudah selesai
        scanType = 'completed';
        alertType = 'secondary';
        message = `
            <h6><i class="fas fa-flag-checkered me-2"></i>Loading Sudah Selesai!</h6>
            <p><strong>Packing List:</strong> ${no_packing}</p>
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Status:</strong> Proses loading sudah selesai</p>
            <p><strong>Waktu:</strong> ${new Date().toLocaleTimeString()}</p>
        `;
        buttonAction = '';
        
    } else {
        // Status tidak valid untuk scan
        scanType = 'invalid';
        alertType = 'danger';
        message = `
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Status Tidak Valid!</h6>
            <p><strong>Packing List:</strong> ${no_packing}</p>
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Status Saat Ini:</strong> ${getStatusText(status_scan_out, status_scan_in)}</p>
            <p><strong>Pesan:</strong> Status tidak memungkinkan untuk scan saat ini</p>
        `;
        buttonAction = '';
    }
    
    resultContent.innerHTML = `
        <div class="alert alert-${alertType}">
            ${message}
        </div>
        ${buttonAction ? `
        <div class="d-grid gap-2">
            <button class="btn btn-kenda" onclick="${buttonAction}">
                <i class="fas fa-check me-2"></i>${getButtonText(scanType)}
            </button>
        </div>
        ` : ''}
    `;
    
    scanResults.style.display = 'block';
    
    // Add to recent scans hanya jika valid
    if (scanType !== 'invalid' && scanType !== 'completed') {
        addToRecentScans(labelCode, scanType, no_packing);
    }
}

function getStatusText(scanOut, scanIn) {
    const outStatus = {
        [SCAN_STATUS.DRAFT]: 'Draft',
        [SCAN_STATUS.PRINTED]: 'Label Tercetak',
        [SCAN_STATUS.SCANNED_OUT]: 'Terkirim'
    }[scanOut] || 'Unknown';
    
    const inStatus = {
        [SCAN_STATUS.PENDING]: 'Belum Loading',
        [SCAN_STATUS.SCANNED_IN]: 'Sedang Loading',
        [SCAN_STATUS.COMPLETED]: 'Selesai Loading'
    }[scanIn] || 'Unknown';
    
    return `${outStatus} | ${inStatus}`;
}

function getButtonText(scanType) {
    const buttonTexts = {
        'out': 'Konfirmasi Scan Keluar Gudang',
        'loading': 'Konfirmasi Scan Loading Kendaraan',
        'complete': 'Konfirmasi Selesai Loading',
        'completed': 'Proses Selesai',
        'invalid': 'Tidak Dapat Diproses'
    };
    return buttonTexts[scanType] || 'Konfirmasi';
}

function showScanError(message) {
    const resultContent = document.getElementById('resultContent');
    const scanResults = document.getElementById('scanResults');
    
    resultContent.innerHTML = `
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Scan Gagal!</h6>
            <p>${message}</p>
        </div>
    `;
    
    scanResults.style.display = 'block';
}

async function confirmScan(labelCode, action, packingId) {
    try {
        const response = await fetch(`${baseUrl}/packing-list/api/process-scan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `packing_id=${packingId}&action=${action}&label_code=${labelCode}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            showScanSuccess(labelCode, action, data.data);
            updateStatisticsAfterScan(action);
        } else {
            alert('Gagal memproses scan: ' + (data.message || 'Unknown error'));
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses scan');
    }
}

function showScanSuccess(labelCode, action, data) {
    let message = '';
    
    switch (action) {
        case SCAN_STATUS.SCANNED_OUT:
            message = `Label ${labelCode} berhasil dicatat sebagai barang keluar gudang.`;
            break;
        case SCAN_STATUS.SCANNED_IN:
            message = `Label ${labelCode} berhasil dikonfirmasi loading ke kendaraan.`;
            break;
        case SCAN_STATUS.COMPLETED:
            message = `Label ${labelCode} berhasil dikonfirmasi selesai loading.`;
            break;
        default:
            message = `Label ${labelCode} berhasil diproses.`;
    }
    
    alert(message);
    
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

function updateStatisticsAfterScan(action) {
    switch (action) {
        case SCAN_STATUS.SCANNED_OUT:
            scannedToday++;
            break;
        case SCAN_STATUS.SCANNED_IN:
            loadedToday++;
            break;
        case SCAN_STATUS.COMPLETED:
            completedToday++;
            break;
    }
    
    updateStatistics();
}

function addToRecentScans(labelCode, scanType, noPacking) {
    const scanTypes = {
        'out': 'Scan keluar gudang',
        'loading': 'Loading kendaraan',
        'complete': 'Selesai loading'
    };
    
    recentScans.unshift({
        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
        label: labelCode,
        type: scanType,
        packing: noPacking,
        description: scanTypes[scanType] || 'Scan diproses'
    });
    
    // Keep only last 5 scans
    if (recentScans.length > 5) {
        recentScans.pop();
    }
    
    updateRecentScans();
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
        const icon = scan.type === 'out' ? 'fa-arrow-right' : 
                    scan.type === 'loading' ? 'fa-truck-loading' : 'fa-check-double';
        const color = scan.type === 'out' ? 'text-primary' : 
                     scan.type === 'loading' ? 'text-info' : 'text-success';
        
        container.innerHTML += `
            <div class="activity-item">
                <div class="activity-time">${scan.time}</div>
                <div class="activity-content">
                    <strong>${scan.packing}</strong> - ${scan.description}
                    <br>
                    <small class="text-muted">Label: ${scan.label}</small>
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

// Base URL untuk API calls
const baseUrl = '<?php echo site_url(); ?>';
</script>
