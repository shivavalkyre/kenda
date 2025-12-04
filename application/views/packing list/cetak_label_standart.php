<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Standard - <?php echo $label_data['label']['label_code']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 80mm 80mm;
            margin: 0;
            padding: 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm;
                height: 80mm;
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
            height: 80mm;
        }
        
        .label-container {
            width: 78mm;
            height: 78mm;
            border: 2px solid #000;
            padding: 3mm;
            font-size: 10px;
            position: relative;
        }
        
        .part-no {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        
        .vendor-name {
            font-size: 12px;
            text-align: center;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .description-box {
            border: 1px solid #000;
            padding: 5px;
            margin: 8px 0;
            text-align: center;
            min-height: 20mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .description-text {
            font-weight: bold;
            font-size: 11px;
        }
        
        .qty-box {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .barcode-container {
            text-align: center;
            margin: 5px 0;
            padding: 3px 0;
        }
        
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 26px;
            letter-spacing: 2px;
            line-height: 1;
        }
        
        .label-code {
            text-align: center;
            font-size: 9px;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        
        .batch-info {
            text-align: center;
            font-size: 10px;
            margin-top: 3px;
            font-weight: bold;
        }
        
        .production-date {
            text-align: center;
            font-size: 9px;
            margin-top: 2px;
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
        <div class="part-no">
            <?php echo $label_data['part_no']; ?>
        </div>
        
        <div class="vendor-name">
            <?php echo $label_data['vendor_name']; ?>
        </div>
        
        <div class="made-in">
            <?php echo $label_data['made_in']; ?>
        </div>
        
        <div class="description-box">
            <div class="description-text">
                PART DESCRIPTION<br>
                <?php echo $label_data['description']; ?>
            </div>
        </div>
        
        <div class="qty-box">
            QTY: <?php echo $label_data['qty']; ?>
        </div>
        
        <div class="production-date">
            <?php echo $label_data['production_date']; ?>
        </div>
        
        <div class="barcode-container">
            <div class="barcode">*<?php echo $label_data['label']['label_code']; ?>*</div>
            <div class="label-code"><?php echo $label_data['label']['label_code']; ?></div>
        </div>
        
        <div class="batch-info">
            <?php echo $label_data['batch_info']; ?>
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
