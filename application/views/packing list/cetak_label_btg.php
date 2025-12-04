<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label BTG - <?php echo $label_data['label']['label_code']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 80mm 120mm;
            margin: 0;
            padding: 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm;
                height: 120mm;
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
            height: 120mm;
        }
        
        .label-container {
            width: 78mm;
            height: 118mm;
            border: 1px solid #000;
            padding: 2mm;
            font-size: 8px;
            position: relative;
        }
        
        .company-name {
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
            text-transform: uppercase;
            line-height: 1.1;
        }
        
        .vendor-name {
            font-size: 10px;
            text-align: center;
            margin-bottom: 3px;
            font-weight: bold;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 9px;
        }
        
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            padding: 0 1px;
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
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 22px;
            letter-spacing: 1px;
            line-height: 1;
        }
        
        .label-code {
            text-align: center;
            font-size: 8px;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        
        .product-info {
            border: 1px solid #000;
            padding: 3px;
            margin: 5px 0;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }
        
        .size-color {
            display: flex;
            justify-content: space-between;
            margin-top: 2px;
        }
        
        .import-info {
            border: 1px solid #000;
            padding: 2px;
            margin: 5px 0;
            font-size: 7px;
            line-height: 1.1;
        }
        
        .cnpj {
            text-align: center;
            font-weight: bold;
            margin-top: 3px;
            font-size: 8px;
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
            font-size: 9px;
            font-weight: bold;
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
            <?php echo $label_data['company_name']; ?>
        </div>
        
        <div class="vendor-name">
            VENDOR NAME: <?php echo $label_data['vendor_name']; ?>
        </div>
        
        <div class="made-in">
            <?php echo $label_data['made_in']; ?>
        </div>
        
        <div class="label-row">
            <span class="label-field">PO#:</span>
            <span class="label-value"><?php echo $label_data['po_no']; ?></span>
        </div>
        
        <div class="label-row">
            <span class="label-field">CODIGO :</span>
            <span class="label-value"><?php echo $label_data['codigo']; ?></span>
        </div>
        
        <div class="product-info">
            <?php echo $label_data['product_name']; ?>
            <div class="size-color">
                <span>SIZE: <?php echo $label_data['size']; ?></span>
                <span>COLOR: <?php echo $label_data['color']; ?></span>
            </div>
        </div>
        
        <div class="label-row">
            <span class="label-field">QTY.:</span>
            <span class="label-value"><?php echo $label_data['qty']; ?> PCS</span>
        </div>
        
        <div class="weight-row">
            <div class="weight-box">
                <div class="weight-label">N.W.</div>
                <div class="weight-value"><?php echo $label_data['nw']; ?></div>
            </div>
            <div class="weight-box">
                <div class="weight-label">G.W.</div>
                <div class="weight-value"><?php echo $label_data['gw']; ?></div>
            </div>
        </div>
        
        <div class="import-info">
            IMPORTADOR POR:<br>
            <?php echo $label_data['import_address']; ?>
        </div>
        
        <div class="cnpj">
            CNPJ: <?php echo $label_data['cnpj']; ?>
        </div>
        
        <div class="barcode-container">
            <div class="barcode">*<?php echo $label_data['label']['label_code']; ?>*</div>
            <div class="label-code"><?php echo $label_data['label']['label_code']; ?></div>
        </div>
        
        <div class="print-time">
            Printed: <?php echo date('d/m/Y H:i', strtotime($print_time)); ?>
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
