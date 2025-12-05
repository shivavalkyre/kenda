
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Packing - <?php echo $packing->no_packing; ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
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
                margin: 20mm;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
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
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .label-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .label-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 2px;
        }
        
        .label-subtitle {
            font-size: 14px;
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .label-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }
        
        .info-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .info-group {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .barcode-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        
        .barcode-container {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            width: 100%;
        }
        
        .barcode-text {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 36px;
            letter-spacing: 3px;
            margin: 10px 0;
            color: #333;
        }
        
        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        
        .qr-container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            width: 100%;
        }
        
        .qr-placeholder {
            width: 150px;
            height: 150px;
            background: #f8f9fa;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .qr-label {
            font-size: 12px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .items-section {
            grid-column: 1 / -1;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .items-title {
            font-size: 14px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        
        .item-card {
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        
        .item-name {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
        }
        
        .label-footer {
            background: #f8f9fa;
            padding: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
        }
        
        .footer-text {
            font-size: 11px;
            color: #6c757d;
            margin: 5px 0;
        }
        
        .warning-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 12px;
            text-align: center;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(220, 53, 69, 0.1);
            z-index: -1;
            font-weight: bold;
            pointer-events: none;
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
            <span>No: <strong><?php echo $packing->no_packing; ?></strong></span>
            <span>Format: <strong>KENDA</strong></span>
        </div>
    </div>
    
    <div class="watermark">KENDA</div>
    
    <div class="label-container">
        <div class="label-header">
            <h1 class="label-title">PACKING LIST</h1>
            <p class="label-subtitle">PT. KENDA RUBBER INDONESIA</p>
        </div>
        
        <div class="label-content">
            <div class="info-section">
                <div class="info-group">
                    <div class="info-label">Nomor Packing</div>
                    <div class="info-value"><?php echo $packing->no_packing; ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Customer</div>
                    <div class="info-value"><?php echo $packing->customer; ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Tanggal Packing</div>
                    <div class="info-value"><?php echo date('d F Y', strtotime($packing->tanggal)); ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Alamat Pengiriman</div>
                    <div class="info-value"><?php echo $packing->alamat ?: '-'; ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <?php 
                        $status_map = [
                            'printed' => 'LABEL TERCETAK',
                            'scanned_out' => 'TERKIRIM',
                            'scanned_in' => 'SEDANG LOADING',
                            'completed' => 'SELESAI LOADING'
                        ];
                        echo $status_map[$packing->status_scan_out] ?? 'DRAFT';
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="barcode-section">
                <div class="barcode-container">
                    <div class="barcode-text">*<?php echo $packing->no_packing; ?>*</div>
                    <div class="barcode-number"><?php echo $packing->no_packing; ?></div>
                </div>
                
                <div class="qr-container">
                    <div class="qr-placeholder">
                        <!-- Placeholder QR Code -->
                        <div style="text-align: center;">
                            <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">QR CODE</div>
                            <div style="font-size: 10px; color: #666;">
                                Scan untuk<br>
                                informasi detail
                            </div>
                        </div>
                    </div>
                    <div class="qr-label">Scan dengan QR Reader</div>
                </div>
            </div>
            
            <div class="items-section">
                <div class="items-title">Daftar Barang</div>
                <div class="items-grid">
                    <?php if (!empty($packing->items) && is_array($packing->items)): ?>
                        <?php foreach ($packing->items as $item): ?>
                        <div class="item-card">
                            <div class="item-name"><?php echo $item->nama; ?></div>
                            <div class="item-details">
                                <span>Kode: <?php echo $item->kode; ?></span>
                                <span>Qty: <?php echo $item->qty; ?> pcs</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="item-card" style="grid-column: 1 / -1; text-align: center;">
                            <div class="item-name">Tidak ada data item</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="label-footer">
            <div class="footer-text">
                <i class="fas fa-info-circle"></i> Label ini dicetak pada: <?php echo $print_time; ?>
            </div>
            <div class="footer-text">
                Total Item: <strong><?php echo $packing->jumlah_item; ?> pcs</strong> | 
                Customer: <strong><?php echo $packing->customer; ?></strong>
            </div>
            <div class="footer-text">
                © <?php echo date('Y'); ?> PT. KENDA RUBBER INDONESIA - All rights reserved
            </div>
        </div>
        
        <div class="warning-note">
            <i class="fas fa-exclamation-triangle"></i> 
            Simpan label ini untuk proses tracking dan verifikasi pengiriman.
            Jangan discan sebelum barang dikeluarkan dari gudang.
        </div>
    </div>
    
    <script>
    // Auto print jika diperlukan
    window.addEventListener('load', function() {
        // Cek jika halaman dibuka dari print button
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            setTimeout(function() {
                window.print();
            }, 1000);
        }
    });
    
    // Handle print events
    window.addEventListener('beforeprint', function() {
        console.log('Mencetak label <?php echo $packing->no_packing; ?>');
    });
    
    window.addEventListener('afterprint', function() {
        console.log('Selesai mencetak');
        // Optionally redirect back after printing
        // setTimeout(function() {
        //     window.history.back();
        // }, 2000);
    });
    
    // Keyboard shortcut
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        
        // Escape to go back
        if (e.key === 'Escape') {
            window.history.back();
        }
    });
    </script>
</body>
</html>
