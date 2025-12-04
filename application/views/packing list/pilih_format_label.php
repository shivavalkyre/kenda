<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tag me-2"></i>Pilih Format Label
                </h1>
                <p class="text-muted">Pilih format label untuk packing list: <?php echo $packing['no_packing'] ?? ''; ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Packing</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>No. Packing</td>
                            <td class="fw-bold"><?php echo $packing['no_packing'] ?? ''; ?></td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td><?php echo $packing['customer'] ?? ''; ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Item</td>
                            <td><?php echo $packing['total_items'] ?? 0; ?> pcs</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td><?php echo date('d/m/Y', strtotime($packing['tanggal'] ?? date('Y-m-d'))); ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="badge bg-warning"><?php echo ucfirst($packing['status_scan_out'] ?? 'printed'); ?></span>
                            </td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="<?php echo site_url('packing-list/view-labels/' . $packing_id); ?>" class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-eye me-2"></i>Lihat Label Tersimpan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pilih Format Label</h5>
                </div>
                <div class="card-body">
                    <form id="formPilihFormat">
                        <input type="hidden" name="packing_id" value="<?php echo $packing_id; ?>">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Label</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="label_type" id="label_single" value="single" checked>
                                    <label class="btn btn-outline-primary" for="label_single">Single Label</label>
                                    
                                    <input type="radio" class="btn-check" name="label_type" id="label_multiple" value="multiple">
                                    <label class="btn btn-outline-primary" for="label_multiple">Multiple Labels</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Label</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="label_count" 
                                       id="label_count"
                                       min="1" 
                                       max="100" 
                                       value="1"
                                       disabled>
                                <div class="form-text">Masukkan jumlah label yang ingin dicetak (1-100)</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Pilih Format Label</label>
                                <div class="row g-3">
                                    <!-- Format KENDA -->
                                    <div class="col-md-3">
                                        <div class="card label-format-card" data-format="kenda">
                                            <div class="card-body text-center">
                                                <div class="label-preview mb-3">
                                                    <div class="preview-box bg-light border rounded p-2">
                                                        <small class="d-block fw-bold">KENDA</small>
                                                        <small class="d-block">TIRE 20" X 1.75</small>
                                                        <small class="d-block">PT. KENDA RUBBER</small>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">Format KENDA</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="label_format" 
                                                           value="kenda" id="format_kenda" checked>
                                                    <label class="form-check-label" for="format_kenda">
                                                        Pilih
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Format XDS -->
                                    <div class="col-md-3">
                                        <div class="card label-format-card" data-format="xds">
                                            <div class="card-body text-center">
                                                <div class="label-preview mb-3">
                                                    <div class="preview-box bg-light border rounded p-2">
                                                        <small class="d-block fw-bold">XDS</small>
                                                        <small class="d-block">BICYCLE TIRE</small>
                                                        <small class="d-block">CAMBODIA</small>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">Format XDS</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="label_format" 
                                                           value="xds" id="format_xds">
                                                    <label class="form-check-label" for="format_xds">
                                                        Pilih
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Format BTG -->
                                    <div class="col-md-3">
                                        <div class="card label-format-card" data-format="btg">
                                            <div class="card-body text-center">
                                                <div class="label-preview mb-3">
                                                    <div class="preview-box bg-light border rounded p-2">
                                                        <small class="d-block fw-bold">BTG</small>
                                                        <small class="d-block">PACTUAL COMMODITIES</small>
                                                        <small class="d-block">SERTRADING</small>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">Format BTG</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="label_format" 
                                                           value="btg" id="format_btg">
                                                    <label class="form-check-label" for="format_btg">
                                                        Pilih
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Format Standard -->
                                    <div class="col-md-3">
                                        <div class="card label-format-card" data-format="standard">
                                            <div class="card-body text-center">
                                                <div class="label-preview mb-3">
                                                    <div class="preview-box bg-light border rounded p-2">
                                                        <small class="d-block fw-bold">STANDARD</small>
                                                        <small class="d-block">VENDOR: KENDA</small>
                                                        <small class="d-block">MADE IN INDONESIA</small>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">Format Standard</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="label_format" 
                                                           value="standard" id="format_standard">
                                                    <label class="form-check-label" for="format_standard">
                                                        Pilih
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </button>
                                    <button type="submit" class="btn btn-kenda">
                                        <i class="fas fa-print me-2"></i>Generate & Cetak Label
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?php echo site_url(); ?>';
    
    // Handle radio button selection
    document.querySelectorAll('.label-format-card').forEach(card => {
        card.addEventListener('click', function() {
            const format = this.getAttribute('data-format');
            document.getElementById('format_' + format).checked = true;
            document.querySelectorAll('.label-format-card').forEach(c => {
                c.classList.remove('selected');
            });
            this.classList.add('selected');
        });
    });

    // Handle label type change
    document.querySelectorAll('input[name="label_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const labelCount = document.getElementById('label_count');
            if (this.value === 'multiple') {
                labelCount.disabled = false;
                labelCount.focus();
            } else {
                labelCount.disabled = true;
                labelCount.value = '1';
            }
        });
    });

    // Form submission
    document.getElementById('formPilihFormat').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi input number
        const labelCountInput = document.getElementById('label_count');
        const labelCount = parseInt(labelCountInput.value);
        
        if (isNaN(labelCount) || labelCount < 1 || labelCount > 100) {
            alert('Jumlah label harus antara 1 sampai 100');
            labelCountInput.focus();
            return;
        }
        
        // Kumpulkan data form
        const formData = new URLSearchParams();
        formData.append('packing_id', this.querySelector('[name="packing_id"]').value);
        formData.append('label_count', labelCount);
        formData.append('label_format', this.querySelector('[name="label_format"]:checked').value);
        formData.append('label_type', this.querySelector('[name="label_type"]:checked').value);
        
        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        submitBtn.disabled = true;
        
        // Gunakan endpoint yang benar
        fetch('<?php echo site_url("gudang/api_generate_label_format"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => {
            // Cek jika response valid
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug
            
            if (data.success) {
                // Get label IDs
                const labelIds = data.data.labels.map(label => label.id);
                
                // Jika multiple labels, open print window untuk semua
                const printUrl = '<?php echo site_url("gudang/cetak_multiple_labels"); ?>' + 
                               '?ids=' + labelIds.join(',') + 
                               '&format=' + formData.get('label_format') + 
                               '&autoprint=1';
                
                console.log('Opening print URL:', printUrl); // Debug
                
                // Open print window
                const printWindow = window.open(printUrl, '_blank');
                
                // Redirect back to packing list after delay
                setTimeout(() => {
                    window.location.href = '<?php echo site_url("packing_list"); ?>';
                }, 3000);
                
            } else {
                alert('Error: ' + (data.message || 'Unknown error occurred'));
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate label: ' + error.message);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>

<style>
.label-format-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    height: 100%;
}

.label-format-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.label-format-card.selected {
    border-color: #28a745;
    background-color: #f8fff9;
}

.preview-box {
    height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.btn-group .btn {
    padding: 10px 20px;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-check {
    margin-top: 0.5rem;
}

/* Style untuk input number */
#label_count {
    text-align: center;
    font-weight: bold;
}

#label_count:disabled {
    background-color: #f8f9fa;
    cursor: not-allowed;
}
</style>
