<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Multiple Labels - KENDA</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        /* Ukuran A6: 105mm x 148mm */
        @page {
            size: A6 portrait;
            margin: 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 105mm;
                height: 148mm;
            }
            
            .no-print {
                display: none !important;
            }
            
            .label-page {
                page-break-after: always;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        /* Container utama per halaman */
        .label-page {
            width: 105mm;
            height: 148mm;
            position: relative;
            page-break-after: always;
        }
        
        /* Container label */
        .label-container {
            width: 100mm;
            height: 140mm;
            border: 1px solid #000;
            margin: 4mm auto;
            padding: 3mm;
            font-size: 9px;
            position: relative;
            box-sizing: border-box;
        }
        
        /* Header perusahaan */
        .company-header {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        
        /* Baris data */
        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        
        .label-title {
            font-weight: bold;
            min-width: 35%;
        }
        
        .label-value {
            text-align: right;
            min-width: 65%;
            word-break: break-word;
        }
        
        /* Barcode styling */
        .barcode-section {
            text-align: center;
            margin: 6px 0;
            padding: 4px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 24px;
            letter-spacing: 2px;
            line-height: 1;
        }
        
        .barcode-text {
            font-size: 8px;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        
        /* QR Code section */
        .qr-section {
            text-align: center;
            margin: 5px 0;
            padding: 3px;
        }
        
        .qr-placeholder {
            width: 20mm;
            height: 20mm;
            border: 1px solid #000;
            margin: 2px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
        
        .qr-placeholder span {
            font-size: 7px;
            color: #666;
            text-align: center;
        }
        
        /* Made in section */
        .made-in {
            text-align: center;
            font-weight: bold;
            position: absolute;
            bottom: 10mm;
            left: 0;
            right: 0;
            border-top: 2px solid #000;
            padding-top: 2px;
            font-size: 10px;
        }
        
        /* Footer info */
        .footer-info {
            position: absolute;
            bottom: 2mm;
            left: 2mm;
            right: 2mm;
            font-size: 7px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
        
        /* Description box */
        .description-box {
            border: 1px solid #000;
            padding: 3px;
            margin: 5px 0;
            min-height: 15mm;
            font-size: 8px;
            line-height: 1.3;
            background: #f9f9f9;
        }
        
        /* Weight section */
        .weight-section {
            display: flex;
            justify-content: space-around;
            margin: 4px 0;
            padding: 3px;
            border: 1px solid #000;
        }
        
        .weight-item {
            text-align: center;
        }
        
        .weight-value {
            font-size: 10px;
            font-weight: bold;
        }
        
        .weight-label {
            font-size: 7px;
        }
        
        /* Counter label */
        .counter {
            position: absolute;
            top: 2mm;
            right: 2mm;
            font-size: 7px;
            background: #f0f0f0;
            padding: 1px 3px;
            border-radius: 2px;
        }
        
        /* Print controls */
        .print-controls {
            text-align: center;
            margin: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
        }
        
        .btn-close {
            background: #dc3545;
            color: white;
        }
        
        @media screen {
            body {
                background-color: #f5f5f5;
                padding: 20px;
            }
            
            .label-page {
                margin: 0 auto 20px auto;
                background: white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                border-radius: 4px;
                overflow: hidden;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> CETAK <?php echo count($labels_data); ?> LABEL
        </button>
        <button onclick="window.close()" class="btn btn-close">
            <i class="fas fa-times"></i> TUTUP
        </button>
        <div style="margin-top: 10px; font-size: 12px; color: #666;">
            Format: KENDA | Ukuran: A6 | Total: <?php echo count($labels_data); ?> label
        </div>
    </div>

    <!-- Labels Container -->
    <?php foreach($labels_data as $index => $label_data): ?>
    <div class="label-page">
        <div class="label-container">
            <!-- Counter -->
            <div class="counter">
                <?php echo ($index + 1) . '/' . count($labels_data); ?>
            </div>
            
            <!-- Company Header -->
            <div class="company-header">
                <?php echo $label_data['company_name']; ?>
            </div>
            
            <!-- PO Number -->
            <div class="data-row">
                <span class="label-title">PO NO:</span>
                <span class="label-value"><?php echo $label_data['po_no']; ?></span>
            </div>
            
            <!-- Order Qty -->
            <div class="data-row">
                <span class="label-title">ORDER QTY:</span>
                <span class="label-value"><?php echo number_format($label_data['order_qty']); ?> PCS</span>
            </div>
            
            <!-- Bale Number -->
            <div class="data-row">
                <span class="label-title">BALE NO:</span>
                <span class="label-value"><?php echo $label_data['bales_no']; ?></span>
            </div>
            
            <!-- Quantity per Bale -->
            <div class="data-row">
                <span class="label-title">QTY PER BALE:</span>
                <span class="label-value"><?php echo $label_data['qty_per_bale']; ?> PCS</span>
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
            </div>
            
            <!-- Item Code -->
            <div class="data-row">
                <span class="label-title">ITEM CODE:</span>
                <span class="label-value" style="font-size: 8px; font-family: monospace;">
                    <?php echo $label_data['item_code']; ?>
                </span>
            </div>
            
            <!-- CFR -->
            <div class="data-row">
                <span class="label-title">CFR:</span>
                <span class="label-value"><?php echo $label_data['cfr']; ?></span>
            </div>
            
            <!-- Description -->
            <div class="description-box">
                <strong>DESCRIPTION:</strong><br>
                <?php echo $label_data['description']; ?>
            </div>
            
            <!-- QR Code Placeholder -->
            <div class="qr-section">
                <div class="qr-placeholder">
                    <span>QR CODE<br>SCAN HERE<br><?php echo substr($label_data['label']['label_code'], 0, 8); ?></span>
                </div>
            </div>
            
            <!-- Barcode -->
            <div class="barcode-section">
                <div class="barcode">*<?php echo $label_data['label']['label_code']; ?>*</div>
                <div class="barcode-text"><?php echo $label_data['label']['label_code']; ?></div>
            </div>
            
            <!-- Made In -->
            <div class="made-in">
                <?php echo $label_data['made_in']; ?>
            </div>
            
            <!-- Footer Info -->
            <div class="footer-info">
                <span>
                    <?php 
                    if (isset($label_data['packing']['customer'])) {
                        echo 'Customer: ' . $label_data['packing']['customer'];
                    }
                    ?>
                </span>
                <span>Print: <?php echo date('d/m/Y H:i', strtotime($print_time)); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script>
        // Auto print jika parameter autoprint=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === '1') {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 1000);
            };
            
            window.onafterprint = function() {
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        }
        
        // Menampilkan info debugging
        console.log('Total labels: <?php echo count($labels_data); ?>');
        console.log('Label data sample:', <?php echo json_encode($labels_data[0] ?? []); ?>);
    </script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
