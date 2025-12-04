<!-- application/views/label/cetak_multiple_standard_a6.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Multiple Labels STANDARD</title>
    <style>
        /* Ukuran A6 Portrait: 105mm x 148mm */
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
            
            .a6-page {
                page-break-after: always;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-size: 9px;
        }
        
        /* Container utama - ukuran A6 */
        .a6-page {
            width: 105mm;
            height: 148mm;
            position: relative;
            page-break-after: always;
        }
        
        /* Container label */
        .label-container {
            width: 98mm;
            height: 138mm;
            border: 1px solid #000;
            margin: 5mm auto;
            padding: 3mm;
            position: relative;
            box-sizing: border-box;
        }
        
        .vendor-name {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
        }
        
        .part-no {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .description-box {
            border: 2px solid #000;
            padding: 5px;
            text-align: center;
            margin: 8px 0;
            font-weight: bold;
            font-size: 10px;
            min-height: 25mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-box {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            margin: 5px 0;
            font-weight: bold;
            font-size: 11px;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin: 8px 0;
            font-size: 10px;
            border: 1px solid #000;
            padding: 3px;
        }
        
        .production-info {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 9px;
        }
        
        .label-counter {
            position: absolute;
            top: 1mm;
            right: 1mm;
            font-size: 7px;
            background: #f0f0f0;
            padding: 1px 3px;
            border-radius: 2px;
        }
        
        .print-date {
            position: absolute;
            bottom: 1mm;
            left: 1mm;
            font-size: 6px;
            color: #666;
        }
        
        .label-code {
            position: absolute;
            bottom: 1mm;
            right: 1mm;
            font-size: 7px;
            font-weight: bold;
        }
        
        .print-controls {
            text-align: center;
            margin: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        
        .btn-print {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        @media screen {
            body {
                background-color: #f5f5f5;
                padding: 20px;
            }
            
            .a6-page {
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
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak <?php echo count($labels_data); ?> Label STANDARD
        </button>
        <button onclick="window.close()" class="btn-close">
            <i class="fas fa-times"></i> Tutup
        </button>
        <div style="margin-top: 10px; color: #666;">
            <small>Format: STANDARD | Ukuran: A6 Portrait | Total: <?php echo count($labels_data); ?> label</small>
        </div>
    </div>

    <!-- Container untuk semua label -->
    <?php foreach($labels_data as $index => $label_data): ?>
    <div class="a6-page">
        <div class="label-container">
            <!-- Label counter -->
            <div class="label-counter">
                <?php echo $index + 1; ?>/<?php echo count($labels_data); ?>
            </div>
            
            <!-- Vendor Name -->
            <div class="vendor-name">
                VENDOR: <?php echo $label_data['vendor_name'] ?? 'KENDA RUBBER'; ?>
            </div>
            
            <!-- Part Number -->
            <div class="part-no">
                PART NO: <?php echo $label_data['part_no'] ?? '757745'; ?>
            </div>
            
            <!-- Description -->
            <div class="description-box">
                <?php echo $label_data['description'] ?? '700*28/32C R/V -22*28T 48L NI'; ?>
            </div>
            
            <!-- Quantity -->
            <div class="quantity-box">
                QTY: <?php echo $label_data['qty'] ?? 50; ?> PCS
            </div>
            
            <!-- Made In -->
            <div class="made-in">
                <?php echo $label_data['made_in'] ?? 'MADE IN INDONESIA'; ?>
            </div>
            
            <!-- Production Information -->
            <div class="production-info">
                <div>
                    PROD DATE:<br>
                    <strong><?php echo $label_data['production_date'] ?? date('Ymd'); ?></strong>
                </div>
                <div>
                    BATCH:<br>
                    <strong><?php echo $label_data['batch_info'] ?? '29 OF 44'; ?></strong>
                </div>
            </div>
            
            <!-- Label Code -->
            <div class="label-code">
                <?php echo $label_data['label']['label_code']; ?>
            </div>
            
            <!-- Print Date -->
            <div class="print-date">
                <?php echo date('d/m/Y', strtotime($print_time)); ?>
            </div>
            
            <!-- Bale Information (jika ada) -->
            <?php if(!empty($label_data['label']['bale_number'])): ?>
            <div style="position: absolute; top: 1mm; left: 1mm; font-size: 8px; font-weight: bold;">
                BALE: <?php echo $label_data['label']['bale_number']; ?>/<?php echo $label_data['label']['total_bales']; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <script>
        // Auto print jika diakses dengan parameter autoprint
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === '1') {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 1000);
            };
        }
        
        // Setelah print, tutup window jika dari autoprint
        window.onafterprint = function() {
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function() {
                    window.close();
                }, 1000);
            }
        };
    </script>
</body>
</html>
