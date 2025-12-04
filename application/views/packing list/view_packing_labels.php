<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tags me-2"></i>Daftar Label Packing
                </h1>
                <p class="text-muted">Label untuk packing list: <?php echo $packing['no_packing'] ?? ''; ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
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
                            <td>Total Item</td>
                            <td><?php echo $packing['total_items'] ?? 0; ?> pcs</td>
                        </tr>
                        <tr>
                            <td>Total Label</td>
                            <td><?php echo count($labels); ?> labels</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="<?php echo site_url('packing-list/pilih-format/' . $packing_id); ?>" class="btn btn-kenda btn-sm w-100">
                            <i class="fas fa-plus me-2"></i>Tambah Label Baru
                        </a>
                        <a href="<?php echo site_url('packing_list'); ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Packing List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Label</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($labels)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada label untuk packing list ini</p>
                            <a href="<?php echo site_url('packing-list/pilih-format/' . $packing_id); ?>" class="btn btn-kenda">
                                <i class="fas fa-plus me-2"></i>Buat Label Pertama
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Label</th>
                                        <th>Format</th>
                                        <th>Tipe</th>
                                        <th>Bale Info</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($labels as $index => $label): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td>
                                                <span class="badge bg-dark"><?php echo $label['label_code']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-uppercase"><?php echo $label['label_format']; ?></span>
                                            </td>
                                            <td>
                                                <?php 
                                                $type_badge = [
                                                    'single' => 'bg-primary',
                                                    'master' => 'bg-warning',
                                                    'child' => 'bg-success'
                                                ];
                                                ?>
                                                <span class="badge <?php echo $type_badge[$label['label_type']] ?? 'bg-secondary'; ?>">
                                                    <?php echo ucfirst($label['label_type']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($label['label_type'] == 'child'): ?>
                                                    <?php echo $label['bale_number']; ?>/<?php echo $label['total_bales']; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $status_badge = [
                                                    'active' => 'bg-secondary',
                                                    'printed' => 'bg-warning',
                                                    'scanned_out' => 'bg-success',
                                                    'scanned_in' => 'bg-primary',
                                                    'completed' => 'bg-info',
                                                    'void' => 'bg-danger'
                                                ];
                                                ?>
                                                <span class="badge <?php echo $status_badge[$label['status']] ?? 'bg-secondary'; ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $label['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo date('d/m/Y H:i', strtotime($label['created_at'])); ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo site_url('packing-list/cetak-format/' . $label['id'] . '/' . $label['label_format']); ?>" 
                                                       target="_blank" class="btn btn-outline-primary" title="Cetak">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <?php if($label['status'] == 'active'): ?>
                                                        <button onclick="updateLabelStatus(<?php echo $label['id']; ?>, 'printed')" 
                                                                class="btn btn-outline-success" title="Tandai sebagai Printed">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="printAllLabels()" class="btn btn-kenda">
                                <i class="fas fa-print me-2"></i>Cetak Semua Label
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printAllLabels() {
    const labelIds = <?php echo json_encode(array_column($labels, 'id')); ?>;
    if (labelIds.length > 0) {
        // Pastikan format URL benar
        const url = '<?php echo site_url("packing-list/cetak-multiple-format"); ?>' + 
                    '?ids=' + labelIds.join(',') + 
                    '&format=kenda' + 
                    '&autoprint=1';
        window.open(url, '_blank');
    } else {
        alert('Tidak ada label untuk dicetak');
    }
}

function updateLabelStatus(labelId, status) {
    if (confirm('Apakah Anda yakin ingin mengupdate status label?')) {
        fetch('<?php echo site_url("labels/api/update-status"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'label_id=' + labelId + '&status=' + status
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status label berhasil diupdate');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>
