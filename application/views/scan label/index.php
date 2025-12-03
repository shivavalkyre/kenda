<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                        <button class="btn btn-kenda" onclick="startScanning()">
                            <i class="fas fa-play me-2"></i>Mulai Scan
                        </button>
                    </div>
                </div>
                
                <!-- Manual Input -->
                <div class="mb-3">
                    <label for="manualInput" class="form-label">Atau input manual kode label:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="manualInput" 
                               placeholder="Masukkan kode label (contoh: LBL001)" 
                               pattern="LBL\d{3,}"
                               title="Format: LBL diikuti angka (contoh: LBL001)">
                        <button class="btn btn-kenda" onclick="processManualInput()">
                            <i class="fas fa-check"></i> Proses
                        </button>
                    </div>
                    <small class="text-muted">Format: LBL + Nomor Packing (contoh: LBL001 untuk Packing List #1)</small>
                </div>
                
                <!-- Scan Results -->
                <div id="scanResults" style="display: none;">
                    <h6 class="section-title">Hasil Scan</h6>
                    <div id="resultContent" class="scan-result-container"></div>
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
                        <div class="stat-label">Terscan Out</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-number text-success" id="todayLoaded">0</div>
                        <div class="stat-label">Sedang Loading</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-number text-info" id="todayCompleted">0</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <button class="btn btn-sm btn-outline-secondary" onclick="refreshStatistics()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh Statistik
                    </button>
                </div>
            </div>
            
            <!-- Recent Scans -->
            <div class="chart-card">
                <h4 class="section-title">Aktivitas Scan Terbaru</h4>
                <div class="activity-list" id="recentScans">
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-spinner fa-spin me-2"></i> Memuat aktivitas scan...
                    </div>
                </div>
                <div class="text-end mt-2">
                    <small class="text-muted">Update otomatis setiap 30 detik</small>
                </div>
            </div>
            
            <!-- Status Legend -->
            <div class="chart-card mt-4">
                <h6 class="section-title">Keterangan Status</h6>
                <div class="row">
                    <div class="col-6 mb-2">
                        <span class="badge bg-warning"><i class="fas fa-print me-1"></i>Printed</span>
                        <small class="d-block text-muted">Label sudah dicetak</small>
                    </div>
                    <div class="col-6 mb-2">
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Scanned Out</span>
                        <small class="d-block text-muted">Barang keluar gudang</small>
                    </div>
                    <div class="col-6 mb-2">
                        <span class="badge bg-primary"><i class="fas fa-truck-loading me-1"></i>Scanned In</span>
                        <small class="d-block text-muted">Sedang loading</small>
                    </div>
                    <div class="col-6 mb-2">
                        <span class="badge bg-info"><i class="fas fa-flag-checkered me-1"></i>Completed</span>
                        <small class="d-block text-muted">Loading selesai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scan Modal (Optional) -->
<div class="modal fade" id="scanConfirmModal" tabindex="-1" aria-labelledby="scanConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanConfirmModalLabel">Konfirmasi Scan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="scanConfirmContent">
                <!-- Content akan diisi via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-kenda" id="confirmScanBtn">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<style>
.scan-result-container {
    max-height: 400px;
    overflow-y: auto;
}

.status-badge {
    font-size: 0.85rem;
    padding: 0.25rem 0.5rem;
}

.activity-item {
    border-left: 3px solid #e9ecef;
    padding-left: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.activity-item:hover {
    border-left-color: #007bff;
    background-color: #f8f9fa;
}

.activity-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.activity-content {
    font-size: 0.9rem;
}

#cameraPreview {
    position: relative;
    overflow: hidden;
}

.scan-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 2px solid #28a745;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { border-color: #28a745; }
    50% { border-color: #20c997; }
    100% { border-color: #28a745; }
}
</style>

<script>
// Status constants yang sinkron dengan packing list
const SCAN_STATUS = {
    PRINTED: 'printed',
    SCANNED_OUT: 'scanned_out',
    SCANNED_IN: 'scanned_in',
    COMPLETED: 'completed',
    PENDING: 'pending'
};

const baseUrl = '<?php echo base_url(); ?>';
let currentScanData = null;
let autoRefreshInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadTodayStats();
    loadRecentScans();
    
    // Setup auto-refresh for recent scans
    startAutoRefresh();
    
    // Handle Enter key in manual input
    document.getElementById('manualInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            processManualInput();
        }
    });
    
    // Handle modal confirmation
    document.getElementById('confirmScanBtn').addEventListener('click', function() {
        if (currentScanData) {
            confirmScanProcess(currentScanData);
        }
    });
});

function startAutoRefresh() {
    // Auto refresh every 30 seconds
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
    
    autoRefreshInterval = setInterval(() => {
        loadRecentScans();
        loadTodayStats();
    }, 30000);
}

function startScanning() {
    // Untuk demo, kita gunakan simulasi
    // Dalam implementasi sebenarnya, ini akan mengakses kamera
    
    document.getElementById('cameraPreview').innerHTML = `
        <div class="text-center p-4">
            <div class="scan-overlay"></div>
            <div class="spinner-border text-kenda-red mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Membuka kamera...</p>
            <p class="text-muted small">Arahkan kamera ke barcode/qrcode label</p>
            <div class="mt-3">
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="stopScanning()">
                    <i class="fas fa-stop me-1"></i>Stop
                </button>
                <button class="btn btn-sm btn-kenda" onclick="simulateScan()">
                    <i class="fas fa-barcode me-1"></i>Simulasi Scan
                </button>
            </div>
        </div>
    `;
}

function stopScanning() {
    document.getElementById('cameraPreview').innerHTML = `
        <div class="text-center text-muted">
            <i class="fas fa-camera fa-3x mb-3"></i>
            <p>Kamera siap untuk scanning</p>
            <button class="btn btn-kenda" onclick="startScanning()">
                <i class="fas fa-play me-2"></i>Mulai Scan
            </button>
        </div>
    `;
}

function simulateScan() {
    // Generate random packing ID untuk simulasi
    const randomId = Math.floor(Math.random() * 50) + 1;
    const labelCode = 'LBL' + randomId.toString().padStart(3, '0');
    processLabelScan(labelCode);
}

function processManualInput() {
    const input = document.getElementById('manualInput').value.trim().toUpperCase();
    
    if (!input) {
        showToast('Masukkan kode label!', 'warning');
        return;
    }
    
    // Validasi format label
    if (!/^LBL\d{3,}$/.test(input)) {
        showToast('Format label tidak valid! Gunakan format: LBL001', 'error');
        return;
    }
    
    processLabelScan(input);
    document.getElementById('manualInput').value = '';
}

async function processLabelScan(labelCode) {
    try {
        showLoading('Memproses label...');
        
        const response = await fetch(`${baseUrl}gudang/api_check_label/${labelCode}`);
        const data = await response.json();
        
        hideLoading();
        
        if (data.success) {
            displayScanResult(data.data, labelCode);
        } else {
            showScanError(data.message || 'Label tidak ditemukan');
        }
    } catch (error) {
        hideLoading();
        console.error('Error:', error);
        showScanError('Terjadi kesalahan saat memproses scan');
    }
}

function displayScanResult(packingData, labelCode) {
    const resultContent = document.getElementById('resultContent');
    const scanResults = document.getElementById('scanResults');
    
    // Tentukan aksi selanjutnya berdasarkan status
    const nextAction = determineNextAction(packingData);
    
    if (!nextAction) {
        showScanResultNoAction(packingData, labelCode);
        return;
    }
    
    const actionConfig = getActionConfig(nextAction);
    
    const statusText = getStatusText(packingData.status_scan_out, packingData.status_scan_in);
    const timelineHtml = generateTimelineHtml(packingData);
    
    resultContent.innerHTML = `
        <div class="alert alert-${actionConfig.alertType}">
            <div class="d-flex align-items-center">
                <i class="fas ${actionConfig.icon} fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">${actionConfig.title}</h5>
                    <p class="mb-0"><strong>Label:</strong> ${labelCode}</p>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Detail Packing List</h6>
                <div class="row">
                    <div class="col-6">
                        <p class="mb-1"><strong>No. Packing:</strong></p>
                        <p class="mb-1"><strong>Customer:</strong></p>
                        <p class="mb-1"><strong>Status:</strong></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1">${packingData.no_packing || 'N/A'}</p>
                        <p class="mb-1">${packingData.customer || 'N/A'}</p>
                        <p class="mb-1"><span class="badge ${getStatusBadgeClass(packingData.status_scan_out)}">
                            ${statusText}
                        </span></p>
                    </div>
                </div>
            </div>
        </div>
        
        ${timelineHtml}
        
        <div class="d-grid gap-2">
            <button class="btn btn-lg btn-${actionConfig.buttonColor}" 
                    onclick="showScanConfirmation('${labelCode}', '${nextAction}', ${packingData.id})">
                <i class="fas ${actionConfig.buttonIcon} me-2"></i>${actionConfig.buttonText}
            </button>
            <button class="btn btn-outline-secondary" onclick="resetScanner()">
                <i class="fas fa-redo me-2"></i>Scan Label Lain
            </button>
        </div>
    `;
    
    scanResults.style.display = 'block';
    document.getElementById('cameraPreview').scrollIntoView({ behavior: 'smooth' });
}

function determineNextAction(packingData) {
    const { status_scan_out, status_scan_in } = packingData;
    
    if (status_scan_out === SCAN_STATUS.PRINTED && status_scan_in === SCAN_STATUS.PENDING) {
        return SCAN_STATUS.SCANNED_OUT;
    }
    
    if (status_scan_out === SCAN_STATUS.SCANNED_OUT && status_scan_in === SCAN_STATUS.PENDING) {
        return SCAN_STATUS.SCANNED_IN;
    }
    
    if (status_scan_out === SCAN_STATUS.SCANNED_OUT && status_scan_in === SCAN_STATUS.SCANNED_IN) {
        return SCAN_STATUS.COMPLETED;
    }
    
    return null;
}

function getActionConfig(action) {
    const configs = {
        [SCAN_STATUS.SCANNED_OUT]: {
            title: 'SCAN KELUAR GUDANG',
            icon: 'fa-door-open',
            alertType: 'success',
            buttonText: 'Konfirmasi Scan Out',
            buttonIcon: 'fa-sign-out-alt',
            buttonColor: 'success',
            description: 'Barang akan dikonfirmasi keluar dari gudang'
        },
        [SCAN_STATUS.SCANNED_IN]: {
            title: 'SCAN LOADING KENDARAAN',
            icon: 'fa-truck-loading',
            alertType: 'info',
            buttonText: 'Konfirmasi Scan In',
            buttonIcon: 'fa-sign-in-alt',
            buttonColor: 'primary',
            description: 'Barang akan dikonfirmasi loading ke kendaraan'
        },
        [SCAN_STATUS.COMPLETED]: {
            title: 'SELESAI LOADING',
            icon: 'fa-flag-checkered',
            alertType: 'warning',
            buttonText: 'Konfirmasi Selesai',
            buttonIcon: 'fa-check-double',
            buttonColor: 'warning',
            description: 'Proses loading akan ditandai selesai'
        }
    };
    
    return configs[action] || {};
}

function getStatusText(scanOut, scanIn) {
    const statusMap = {
        [SCAN_STATUS.PRINTED]: 'Label Tercetak',
        [SCAN_STATUS.SCANNED_OUT]: 'Terkirim',
        [SCAN_STATUS.SCANNED_IN]: 'Loading',
        [SCAN_STATUS.COMPLETED]: 'Selesai',
        [SCAN_STATUS.PENDING]: 'Belum Diproses'
    };
    
    if (scanIn === SCAN_STATUS.PENDING) {
        return statusMap[scanOut] || 'Unknown';
    }
    
    return statusMap[scanIn] || 'Unknown';
}

function getStatusBadgeClass(status) {
    const classMap = {
        [SCAN_STATUS.PRINTED]: 'bg-warning',
        [SCAN_STATUS.SCANNED_OUT]: 'bg-success',
        [SCAN_STATUS.SCANNED_IN]: 'bg-primary',
        [SCAN_STATUS.COMPLETED]: 'bg-info',
        [SCAN_STATUS.PENDING]: 'bg-secondary'
    };
    
    return classMap[status] || 'bg-secondary';
}

function generateTimelineHtml(packingData) {
    const steps = [
        { id: 1, status: SCAN_STATUS.PRINTED, label: 'Label Tercetak', active: true },
        { id: 2, status: SCAN_STATUS.SCANNED_OUT, label: 'Scan Out Gudang' },
        { id: 3, status: SCAN_STATUS.SCANNED_IN, label: 'Scan In Kendaraan' },
        { id: 4, status: SCAN_STATUS.COMPLETED, label: 'Selesai Loading' }
    ];
    
    // Tentukan step aktif
    let activeStep = 1;
    if (packingData.status_scan_out === SCAN_STATUS.SCANNED_OUT) {
        activeStep = 2;
    }
    if (packingData.status_scan_in === SCAN_STATUS.SCANNED_IN) {
        activeStep = 3;
    }
    if (packingData.status_scan_in === SCAN_STATUS.COMPLETED) {
        activeStep = 4;
    }
    
    let timelineHtml = `
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-3 text-muted">Progress Packing List</h6>
                <div class="timeline">
    `;
    
    steps.forEach((step, index) => {
        const isActive = step.id <= activeStep;
        const isCurrent = step.id === activeStep;
        
        timelineHtml += `
            <div class="timeline-step ${isActive ? 'active' : ''} ${isCurrent ? 'current' : ''}">
                <div class="timeline-icon">
                    <i class="fas fa-${isActive ? 'check' : 'circle'}"></i>
                </div>
                <div class="timeline-content">
                    <h6 class="mb-0">${step.label}</h6>
                    ${isCurrent ? '<small class="text-success">Sedang diproses</small>' : ''}
                </div>
            </div>
        `;
    });
    
    timelineHtml += `
                </div>
            </div>
        </div>
    `;
    
    return timelineHtml;
}

function showScanResultNoAction(packingData, labelCode) {
    const resultContent = document.getElementById('resultContent');
    const scanResults = document.getElementById('scanResults');
    const statusText = getStatusText(packingData.status_scan_out, packingData.status_scan_in);
    
    resultContent.innerHTML = `
        <div class="alert alert-secondary">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">STATUS TIDAK DAPAT DIPROSES</h5>
                    <p class="mb-0">Label: ${labelCode}</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Detail Packing List</h6>
                <div class="row">
                    <div class="col-6">
                        <p class="mb-1"><strong>No. Packing:</strong></p>
                        <p class="mb-1"><strong>Customer:</strong></p>
                        <p class="mb-1"><strong>Status Saat Ini:</strong></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1">${packingData.no_packing || 'N/A'}</p>
                        <p class="mb-1">${packingData.customer || 'N/A'}</p>
                        <p class="mb-1"><span class="badge ${getStatusBadgeClass(packingData.status_scan_out)}">
                            ${statusText}
                        </span></p>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Status packing list saat ini tidak memungkinkan untuk scan.
                    ${packingData.status_scan_in === SCAN_STATUS.COMPLETED ? 
                        'Loading sudah selesai.' : 
                        'Mohon periksa urutan proses.'}
                </div>
            </div>
        </div>
        
        <div class="d-grid gap-2 mt-3">
            <button class="btn btn-outline-primary" onclick="viewPackingDetail(${packingData.id})">
                <i class="fas fa-eye me-2"></i>Lihat Detail Packing
            </button>
            <button class="btn btn-secondary" onclick="resetScanner()">
                <i class="fas fa-redo me-2"></i>Scan Label Lain
            </button>
        </div>
    `;
    
    scanResults.style.display = 'block';
}

function showScanConfirmation(labelCode, action, packingId) {
    const actionText = getActionConfig(action).description;
    
    document.getElementById('scanConfirmContent').innerHTML = `
        <div class="text-center">
            <i class="fas fa-question-circle fa-3x text-warning mb-3"></i>
            <h5>Konfirmasi ${action === SCAN_STATUS.SCANNED_OUT ? 'Scan Out' : 
                          action === SCAN_STATUS.SCANNED_IN ? 'Scan In' : 'Selesai Loading'}</h5>
            <p class="mb-2"><strong>Label:</strong> ${labelCode}</p>
            <p>${actionText}</p>
            <div class="alert alert-info small">
                <i class="fas fa-info-circle me-1"></i>
                Tindakan ini akan mengupdate status packing list secara permanen.
            </div>
        </div>
    `;
    
    currentScanData = { labelCode, action, packingId };
    
    const modal = new bootstrap.Modal(document.getElementById('scanConfirmModal'));
    modal.show();
}

async function confirmScanProcess(scanData) {
    try {
        const { labelCode, action, packingId } = scanData;
        
        const response = await fetch(`${baseUrl}gudang/api_process_scan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `packing_id=${packingId}&action=${action}&label_code=${labelCode}`
        });
        
        const data = await response.json();
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('scanConfirmModal'));
        modal.hide();
        
        if (data.success) {
            showToast('Scan berhasil diproses!', 'success');
            
            // Update statistics
            loadTodayStats();
            loadRecentScans();
            
            // Reset scanner
            resetScanner();
        } else {
            showToast('Gagal: ' + data.message, 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memproses scan', 'error');
    }
}

function resetScanner() {
    document.getElementById('scanResults').style.display = 'none';
    document.getElementById('cameraPreview').innerHTML = `
        <div class="text-center text-muted">
            <i class="fas fa-camera fa-3x mb-3"></i>
            <p>Kamera siap untuk scanning</p>
            <button class="btn btn-kenda" onclick="startScanning()">
                <i class="fas fa-play me-2"></i>Mulai Scan
            </button>
        </div>
    `;
    
    currentScanData = null;
}

async function loadTodayStats() {
    try {
        const response = await fetch(`${baseUrl}gudang/api_today_scan_stats`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('todayScanned').textContent = data.data.scanned_today || 0;
            document.getElementById('todayLoaded').textContent = data.data.loaded_today || 0;
            document.getElementById('todayCompleted').textContent = data.data.completed_today || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadRecentScans() {
    try {
        const response = await fetch(`${baseUrl}gudang/api_recent_scans/5`);
        const data = await response.json();
        
        const container = document.getElementById('recentScans');
        
        if (data.success && data.data.length > 0) {
            let html = '';
            
            data.data.forEach(scan => {
                const time = new Date(scan.timestamp).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const actionText = scan.action === 'scanned_out' ? 'Scan Out Gudang' :
                                 scan.action === 'scanned_in' ? 'Scan In Loading' :
                                 scan.action === 'completed' ? 'Selesai Loading' : 'Diproses';
                
                const icon = scan.action === 'scanned_out' ? 'fa-arrow-right text-success' :
                           scan.action === 'scanned_in' ? 'fa-truck-loading text-primary' :
                           scan.action === 'completed' ? 'fa-flag-checkered text-info' : 'fa-qrcode';
                
                html += `
                    <div class="activity-item">
                        <div class="activity-time">${time}</div>
                        <div class="activity-content">
                            <div class="d-flex justify-content-between">
                                <strong>${scan.label_code || 'N/A'}</strong>
                                <i class="fas ${icon}"></i>
                            </div>
                            <small class="text-muted">${actionText}</small>
                            <br>
                            <small>${scan.no_packing || ''} - ${scan.customer || ''}</small>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="fas fa-history fa-2x mb-2"></i>
                    <p>Belum ada aktivitas scan hari ini</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading recent scans:', error);
    }
}

function refreshStatistics() {
    loadTodayStats();
    loadRecentScans();
    showToast('Statistik diperbarui', 'info');
}

function viewPackingDetail(packingId) {
    // Redirect ke halaman packing list detail
    window.open(`${baseUrl}gudang/packing_list_detail/${packingId}`, '_blank');
}

// Utility functions
function showToast(message, type = 'info') {
    // Implement toast notification
    const toastId = 'toast-' + Date.now();
    const bgColor = type === 'success' ? 'bg-success' : 
                    type === 'error' ? 'bg-danger' : 
                    type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0 position-fixed bottom-0 end-0 m-3" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                type === 'error' ? 'fa-exclamation-circle' : 
                                type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHTML);
    const toast = new bootstrap.Toast(document.getElementById(toastId));
    toast.show();
    
    setTimeout(() => {
        document.getElementById(toastId)?.remove();
    }, 3000);
}

function showLoading(message = 'Memproses...') {
    // Implement loading indicator
    const loadingId = 'loading-' + Date.now();
    const loadingHTML = `
        <div id="${loadingId}" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
             style="background: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="text-center bg-white p-4 rounded">
                <div class="spinner-border text-kenda-red mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">${message}</p>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', loadingHTML);
    return loadingId;
}

function hideLoading(loadingId = null) {
    if (loadingId) {
        document.getElementById(loadingId)?.remove();
    } else {
        document.querySelectorAll('[id^="loading-"]').forEach(el => el.remove());
    }
}

function showScanError(message) {
    const resultContent = document.getElementById('resultContent');
    const scanResults = document.getElementById('scanResults');
    
    resultContent.innerHTML = `
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">SCAN GAGAL</h5>
                    <p class="mb-0">${message}</p>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-outline-secondary" onclick="resetScanner()">
                <i class="fas fa-redo me-2"></i>Coba Lagi
            </button>
        </div>
    `;
    
    scanResults.style.display = 'block';
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-step {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-step:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    position: absolute;
    left: -30px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #fff;
    border: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.timeline-step.active .timeline-icon {
    border-color: #28a745;
    background-color: #28a745;
    color: white;
}

.timeline-step.current .timeline-icon {
    border-color: #007bff;
    background-color: #007bff;
    color: white;
    animation: pulse-current 1.5s infinite;
}

.timeline-content {
    padding-left: 1rem;
}

@keyframes pulse-current {
    0% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
}
</style>
