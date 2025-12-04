<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label KENDA - <?php echo $label_data['label']['label_code']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        /* Ukuran kertas 100mm x 150mm */
        @page {
            size: 100mm 150mm;
            margin: 0;
            padding: 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 100mm;
                height: 150mm;
            }
            
            .no-print {
                display: none !important;
            }
            
            .label-container {
                page-break-after: always;
            }
            
            /* Pastikan label pas di kertas */
            .label-container {
                width: 96mm;
                height: 146mm;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100mm;
            height: 150mm;
            background: white;
        }
        
        .label-container {
            width: 96mm; /* 100mm - 4mm margin */
            height: 146mm; /* 150mm - 4mm margin */
            border: 1px solid #000;
            padding: 2mm;
            font-size: 10px;
            position: relative;
            box-sizing: border-box;
            margin: 2mm auto;
            background: white;
        }
        
        .company-name {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            text-transform: uppercase;
            padding-bottom: 2px;
            border-bottom: 1px solid #333;
        }
        
        .label-header {
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 4px;
        }
        
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        
        .label-field {
            font-weight: bold;
            min-width: 45%;
            font-size: 10px;
        }
        
        .label-value {
            text-align: right;
            min-width: 55%;
            font-size: 10px;
            font-weight: normal;
        }
        
        .barcode-container {
            text-align: center;
            margin: 8px 0;
            padding: 4px;
            border: 1px dashed #ccc;
            background: #f9f9f9;
        }
        
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 30px;
            letter-spacing: 2px;
            line-height: 1;
            margin-bottom: 3px;
        }
        
        .label-code {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin-top: 5px;
            border-top: 2px solid #000;
            padding-top: 3px;
            font-size: 11px;
        }
        
        .description-box {
            border: 1px solid #000;
            padding: 4px;
            margin: 5px 0;
            font-size: 9px;
            min-height: 25mm;
            background: #f5f5f5;
            line-height: 1.3;
        }
        
        .description-box .label-field {
            font-size: 9px;
            margin-bottom: 2px;
            display: block;
        }
        
        .print-controls {
            text-align: center;
            margin: 10px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .btn-print, .btn-close {
            padding: 10px 20px;
            margin: 0 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
            transition: background 0.3s;
        }
        
        .btn-print:hover {
            background: #0056b3;
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
            transition: background 0.3s;
        }
        
        .btn-close:hover {
            background: #545b62;
        }
        
        .print-time {
            position: absolute;
            bottom: 3mm;
            right: 3mm;
            font-size: 8px;
            color: #666;
        }
        
        .bale-info {
            position: absolute;
            bottom: 3mm;
            left: 3mm;
            font-size: 8px;
            color: #666;
            font-weight: bold;
        }
        
        .qr-code-placeholder {
            width: 25mm;
            height: 25mm;
            border: 1px solid #ddd;
            margin: 5px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
        
        .qr-code-placeholder span {
            font-size: 8px;
            color: #999;
        }
        
        /* Section untuk data packing */
        .packing-info {
            border: 1px solid #ccc;
            padding: 3px;
            margin: 4px 0;
            font-size: 9px;
            background: #fff;
        }
        
        .packing-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
        }
        
        /* Weight section */
        .weight-section {
            display: flex;
            justify-content: space-around;
            margin: 5px 0;
            padding: 3px;
            border: 1px solid #000;
            background: #e9ecef;
        }
        
        .weight-item {
            text-align: center;
        }
        
        .weight-value {
            font-size: 12px;
            font-weight: bold;
        }
        
        .weight-label {
            font-size: 8px;
        }
        
        /* Grid layout untuk memanfaatkan space */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
            margin: 4px 0;
        }
        
        .grid-item {
            border: 1px solid #ddd;
            padding: 2px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> CETAK LABEL
        </button>
        <button onclick="window.close()" class="btn-close">
            <i class="fas fa-times"></i> TUTUP
        </button>
        <p style="margin-top: 10px; font-size: 12px;">
            Ukuran: 100mm x 150mm | 
            Format: <?php echo strtoupper($format); ?>
        </p>
    </div>

    <!-- Label Container -->
    <div class="label-container">
        <!-- Company Name -->
        <div class="company-name">
            <?php echo $label_data['company_name']; ?>
        </div>
        
        <!-- PO Number -->
        <div class="label-header">
            <div class="label-row">
                <span class="label-field">PO NO:</span>
                <span class="label-value"><?php echo $label_data['po_no']; ?></span>
            </div>
        </div>
        
        <!-- Grid Container untuk informasi penting -->
        <div class="grid-container">
            <div class="grid-item">
                <div class="label-row">
                    <span class="label-field">Order Qty:</span>
                    <span class="label-value"><?php echo number_format($label_data['order_qty']); ?></span>
                </div>
            </div>
            <div class="grid-item">
                <div class="label-row">
                    <span class="label-field">Bale:</span>
                    <span class="label-value"><?php echo $label_data['bales_no']; ?></span>
                </div>
            </div>
            <div class="grid-item">
                <div class="label-row">
                    <span class="label-field">Qty/Bale:</span>
                    <span class="label-value"><?php echo $label_data['qty_per_bale']; ?></span>
                </div>
            </div>
            <div class="grid-item">
                <div class="label-row">
                    <span class="label-field">Item:</span>
                    <span class="label-value"><?php echo substr($label_data['item_code'], 0, 15) . '...'; ?></span>
                </div>
            </div>
        </div>
        
        <!-- Weight Section -->
        <div class="weight-section">
            <div class="weight-item">
                <div class="weight-value"><?php echo $label_data['nw']; ?> KG</div>
                <div class="weight-label">NET WEIGHT</div>
            </div>
            <div class="weight-item">
                <div class="weight-value"><?php echo $label_data['gw']; ?> KG</div>
                <div class="weight-label">GROSS WEIGHT</div>
            </div>
            <div class="weight-item">
                <div class="weight-value"><?php echo $label_data['cfr']; ?></div>
                <div class="weight-label">CFR</div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="description-box">
            <div class="label-field">DESCRIPTION:</div>
            <div style="margin-top: 2px; font-size: 10px; line-height: 1.4;">
                <?php echo $label_data['description']; ?>
            </div>
        </div>
        
        <!-- QR Code Placeholder -->
        <div class="qr-code-placeholder">
            <span>QR CODE<br>SCAN HERE</span>
        </div>
        
        <!-- Barcode -->
        <div class="barcode-container">
            <div class="barcode">*<?php echo $label_data['label']['label_code']; ?>*</div>
            <div class="label-code"><?php echo $label_data['label']['label_code']; ?></div>
        </div>
        
        <!-- Made In -->
        <div class="made-in">
            <?php echo $label_data['made_in']; ?>
        </div>
        
        <!-- Packing Info -->
        <div class="packing-info">
            <div class="packing-row">
                <span>Customer:</span>
                <span><?php echo $label_data['packing']['customer'] ?? 'N/A'; ?></span>
            </div>
            <?php if (isset($label_data['label']['bale_number'])): ?>
            <div class="packing-row">
                <span>Bale:</span>
                <span><?php echo $label_data['label']['bale_number'] . ' of ' . $label_data['label']['total_bales']; ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Bale Info -->
        <div class="bale-info">
            <?php 
            if (isset($label_data['label']['bale_number'])) {
                echo 'BALE ' . $label_data['label']['bale_number'] . '/' . $label_data['label']['total_bales'];
            } else {
                echo 'SINGLE PACK';
            }
            ?>
        </div>
        
        <!-- Print Time -->
        <div class="print-time">
            <?php echo date('d/m/Y H:i', strtotime($print_time)); ?>
        </div>
    </div>

    <script>
        // Auto print jika diakses dengan parameter autoprint
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === '1') {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 800);
            };
        }
        
        // Event listener setelah print
        window.onafterprint = function() {
            setTimeout(function() {
                // Optional: redirect ke halaman sebelumnya
                // window.close();
            }, 1500);
        };
        
        // Keyboard shortcut untuk print
        document.addEventListener('keydown', function(e) {
            // Ctrl + P atau Cmd + P
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // Escape untuk close
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>
    
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
