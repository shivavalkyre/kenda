<!-- cetak_label_xds.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label XDS</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 80mm 100mm;
            margin: 0;
            padding: 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm;
                height: 100mm;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 80mm;
            height: 100mm;
        }
        
        .label-container {
            width: 78mm;
            height: 98mm;
            border: 1px solid #000;
            padding: 2mm;
            font-size: 9px;
            position: relative;
        }
        
        .company-name {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .product-name {
            font-size: 11px;
            text-align: center;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 10px;
        }
        
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            padding: 0 2px;
        }
        
        .label-field {
            font-weight: bold;
            min-width: 40%;
        }
        
        .label-value {
            text-align: right;
            min-width: 60%;
        }
        
        .barcode-container {
            text-align: center;
            margin: 8px 0;
            padding: 5px 0;
            border-top: 1px solid #000;
            border-bottom: 1px dashed #000;
        }
        
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 24px;
            letter-spacing: 1px;
            line-height: 1;
        }
        
        .label-code {
            text-align: center;
            font-size: 8px;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        
        .weight-row {
            display: flex;
            justify-content: space-around;
            margin-top: 5px;
            text-align: center;
        }
        
        .weight-box {
            border: 1px solid #000;
            padding: 2px;
            width: 45%;
        }
        
        .weight-label {
            font-weight: bold;
            font-size: 8px;
        }
        
        .weight-value {
            font-size: 10px;
            font-weight: bold;
        }
        
        .print-time {
            position: absolute;
            bottom: 2mm;
            right: 2mm;
            font-size: 7px;
            color: #666;
        }
        
        .print-controls {
            text-align: center;
            margin: 10px;
            padding: 10px;
            background: #f5f5f5;
        }
        
        .size-box {
            border: 1px solid #000;
            padding: 3px;
            margin: 5px 0;
            text-align: center;
            font-weight: bold;
        }
        
        .kenda-size {
            font-size: 8px;
            margin-top: 2px;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Label
        </button>
        <button onclick="window.close()" class="btn-close">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <!-- Label Container -->
    <div class="label-container">
        <div class="company-name">
            <?php echo isset($labels_data[0]['company_name']) ? $labels_data[0]['company_name'] : 'XDS BICYCLE CAMBODIA'; ?>
        </div>
        
        <div class="product-name">
            <?php echo isset($labels_data[0]['product_name']) ? $labels_data[0]['product_name'] : 'BICYCLE TIRE'; ?>
        </div>
        
        <div class="made-in">
            <?php echo isset($labels_data[0]['made_in']) ? $labels_data[0]['made_in'] : 'MADE IN INDONESIA'; ?>
        </div>
        
        <div class="label-row">
            <span class="label-field">PO#:</span>
            <span class="label-value"><?php echo isset($labels_data[0]['po_no']) ? $labels_data[0]['po_no'] : '0-88130-7-1-5'; ?></span>
        </div>
        
        <div class="size-box">
            SIZE : <?php echo isset($labels_data[0]['size']) ? $labels_data[0]['size'] : '650B*50 GRO COMP'; ?>
            <div class="kenda-size">
                KENDA SIZE: <?php echo isset($labels_data[0]['kenda_size']) ? $labels_data[0]['kenda_size'] : '650B*50 TR040 BK/BSK 60TPI R6275*2'; ?>
            </div>
        </div>
        
        <div class="label-row">
            <span class="label-field">ITEM NO :</span>
            <span class="label-value"><?php echo isset($labels_data[0]['item_no']) ? $labels_data[0]['item_no'] : '1110-18373'; ?></span>
        </div>
        
        <div class="label-row">
            <span class="label-field">P'KG NO :</span>
            <span class="label-value"><?php echo isset($labels_data[0]['pkg_no']) ? $labels_data[0]['pkg_no'] : (isset($labels_data[0]['label']['label_code']) ? $labels_data[0]['label']['label_code'] : ''); ?></span>
        </div>
        
        <div class="label-row">
            <span class="label-field">QTY.:</span>
            <span class="label-value"><?php echo isset($labels_data[0]['qty']) ? $labels_data[0]['qty'] : 25; ?> PCS</span>
        </div>
        
        <div class="weight-row">
            <div class="weight-box">
                <div class="weight-label">N.W.</div>
                <div class="weight-value"><?php echo isset($labels_data[0]['nw']) ? $labels_data[0]['nw'] : '19.88'; ?></div>
            </div>
            <div class="weight-box">
                <div class="weight-label">G.W.</div>
                <div class="weight-value"><?php echo isset($labels_data[0]['gw']) ? $labels_data[0]['gw'] : '26.25'; ?></div>
            </div>
        </div>
        
        <div class="barcode-container">
            <?php 
            $label_code = isset($labels_data[0]['label']['label_code']) ? $labels_data[0]['label']['label_code'] : 
                         (isset($labels_data[0]['label_code']) ? $labels_data[0]['label_code'] : 'LBL000001');
            ?>
            <div class="barcode">*<?php echo $label_code; ?>*</div>
            <div class="label-code"><?php echo $label_code; ?></div>
        </div>
        
        <div class="print-time">
            Printed: <?php echo isset($print_time) ? date('d/m/Y H:i', strtotime($print_time)) : date('d/m/Y H:i'); ?>
        </div>
    </div>

    <script>
        // Auto print jika diakses dengan parameter autoprint
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === '1') {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>
