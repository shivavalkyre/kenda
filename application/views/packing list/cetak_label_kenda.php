
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Packing - KENDA</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .label-container {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f5f5f5;
        }
        
        .print-controls {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-print {
            background: #dc3545;
            color: white;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
        }
        
        .label-container {
            max-width: 297mm;
            margin: 0 auto;
        }
        
        .label-sheet {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .label-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            page-break-inside: avoid;
            position: relative;
        }
        
        .label-header {
            border-bottom: 2px solid #dc3545;
            padding-bottom: 10px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .label-title {
            color: #dc3545;
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        
        .label-subtitle {
            color: #666;
            font-size: 12px;
            margin: 5px 0 0 0;
        }
        
        .label-content {
            display: grid;
            grid-template-columns: 1fr 150px;
            gap: 15px;
        }
        
        .label-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #eee;
            padding-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 12px;
        }
        
        .info-value {
            font-weight: bold;
            color: #333;
            font-size: 13px;
            text-align: right;
        }
        
        .barcode-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .barcode-container {
            text-align: center;
        }
        
        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 5px;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .qr-container {
            text-align: center;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            background: white;
        }
        
        .qr-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .items-section {
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        .items-title {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .items-list {
            font-size: 11px;
            line-height: 1.4;
            color: #666;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        
        .warning-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 8px;
            border-radius: 4px;
            font-size: 10px;
            margin-top: 10px;
            text-align: center;
        }
        
        /* Styling untuk barcode teks */
        .barcode-text {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 24px;
            letter-spacing: 2px;
            padding: 5px;
            background: white;
            border: 1px solid #ddd;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            opacity: 0.1;
            font-size: 80px;
            transform: rotate(-45deg);
            z-index: -1;
            color: #dc3545;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .label-sheet {
                grid-template-columns: 1fr;
            }
            
            .label-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="print-controls no-print">
        <button class="btn btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Label
        </button>
        <button class="btn btn-back" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <div style="margin-left: auto; display: flex; gap: 10px; align-items: center;">
            <span>Format: <strong>KENDA</strong></span>
            <span>Jumlah: <strong><?php echo count($packing_lists); ?> Label</strong></span>
        </div>
    </div>
    
    <div class="label-container">
        <div class="label-sheet">
            <?php foreach ($packing_lists as $index => $packing): ?>
            <div class="label-item">
                <div class="watermark">KENDA</div>
                
                <div class="label-header">
                    <h2 class="label-title">PACKING LIST</h2>
                    <p class="label-subtitle">PT. KENDA RUBBER INDONESIA</p>
                </div>
                
                <div class="label-content">
                    <div class="label-info">
                        <div class="info-row">
                            <span class="info-label">NO. PACKING:</span>
                            <span class="info-value"><?php echo $packing->no_packing; ?></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">CUSTOMER:</span>
                            <span class="info-value"><?php echo $packing->customer; ?></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">TANGGAL:</span>
                            <span class="info-value"><?php echo date('d/m/Y', strtotime($packing->tanggal)); ?></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">TOTAL ITEM:</span>
                            <span class="info-value"><?php echo $packing->jumlah_item; ?> pcs</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">STATUS:</span>
                            <span class="info-value">
                                <?php 
                                $status_map = [
                                    'printed' => 'LABEL TERCETAK',
                                    'scanned_out' => 'TERKIRIM',
                                    'scanned_in' => 'LOADING',
                                    'completed' => 'SELESAI'
                                ];
                                echo $status_map[$packing->status_scan_out] ?? 'DRAFT';
                                ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($packing->alamat)): ?>
                        <div class="info-row">
                            <span class="info-label">ALAMAT:</span>
                            <span class="info-value" style="font-size: 11px;"><?php echo substr($packing->alamat, 0, 50); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="barcode-section">
                        <!-- Barcode -->
                        <div class="barcode-container">
                            <div class="barcode-text">*<?php echo $packing->no_packing; ?>*</div>
                            <div class="barcode-number"><?php echo $packing->no_packing; ?></div>
                        </div>
                        
                        <!-- QR Code -->
                        <div class="qr-container">
                            <div style="width: 100px; height: 100px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                                <!-- Placeholder untuk QR Code -->
                                <div style="font-size: 12px; color: #666; text-align: center;">
                                    QR CODE<br>
                                    <small><?php echo substr($packing->no_packing, 0, 8); ?></small>
                                </div>
                            </div>
                            <div class="qr-label">SCAN FOR DETAILS</div>
                        </div>
                    </div>
                </div>
                
                <div class="items-section">
                    <div class="items-title">ITEM TERKIRIM:</div>
                    <div class="items-list">
                        <?php 
                        $items_display = [];
                        if (!empty($packing->items) && is_array($packing->items)) {
                            foreach ($packing->items as $item) {
                                $items_display[] = $item->nama . ' (' . $item->qty . ')';
                            }
                            echo implode(', ', array_slice($items_display, 0, 3));
                            if (count($items_display) > 3) {
                                echo ' ... +' . (count($items_display) - 3) . ' more';
                            }
                        } elseif (!empty($packing->item_names)) {
                            echo $packing->item_names;
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="footer">
                    <div>Printed: <?php echo $print_date; ?></div>
                    <div>© <?php echo date('Y'); ?> PT. KENDA RUBBER INDONESIA</div>
                </div>
                
                <div class="warning-note">
                    <i class="fas fa-exclamation-triangle"></i> Simpan label ini untuk tracking pengiriman
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script>
    // Auto print jika parameter autoprint=1
    <?php if ($autoprint == 1): ?>
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
            
            // Kembali ke halaman sebelumnya setelah cetak
            setTimeout(function() {
                window.history.back();
            }, 1000);
        }, 500);
    });
    <?php endif; ?>
    
    // Handle sebelum cetak
    window.addEventListener('beforeprint', function() {
        console.log('Mencetak label...');
    });
    
    // Handle setelah cetak
    window.addEventListener('afterprint', function() {
        console.log('Selesai mencetak');
    });
    
    // Keyboard shortcut untuk print (Ctrl+P)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });
    </script>
</body>
</html>
