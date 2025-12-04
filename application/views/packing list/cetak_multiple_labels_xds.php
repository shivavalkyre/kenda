<!-- application/views/label/cetak_multiple_xds.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Multiple Labels XDS</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>

		  /* Font barcode alternatif jika Libre Barcode tidak tersedia */
    @font-face {
        font-family: 'BarcodeFont';
        src: url('https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap');
    }
    
    .barcode {
        font-family: 'Libre Barcode 128', 'BarcodeFont', monospace;
        font-size: 28px; /* Perbesar sedikit */
        letter-spacing: 2px;
        line-height: 1;
        text-align: center;
    }
    
    /* Fallback untuk browser yang tidak support font barcode */
    .barcode-fallback {
        font-family: monospace;
        font-size: 16px;
        letter-spacing: 3px;
        text-align: center;
        border: 1px solid #000;
        padding: 5px;
        background: #f5f5f5;
    }
		
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
            
            .page-break {
                page-break-after: always;
            }
            
            .label-container {
                page-break-inside: avoid;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 105mm;
            min-height: 148mm;
            background-color: #fff;
        }
        
        /* Container utama - ukuran A6 */
        .a6-page {
            width: 105mm;
            height: 148mm;
            position: relative;
            page-break-after: always;
        }
        
        /* Container label - sedikit lebih kecil dari halaman */
        .label-container {
            width: 100mm;
            height: 140mm;
            border: 1px solid #000;
            margin: 4mm auto;
            padding: 2mm;
            font-size: 9px;
            position: relative;
            box-sizing: border-box;
        }
        
        .company-name {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        
        .product-name {
            font-size: 10px;
            text-align: center;
            margin-bottom: 3px;
            font-weight: bold;
            line-height: 1.2;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 9px;
            line-height: 1.2;
        }
        
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            padding: 0 1px;
            line-height: 1.1;
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
            margin: 6px 0;
            padding: 4px 0;
            border-top: 1px solid #000;
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
            font-size: 7px;
            margin-top: 1px;
            letter-spacing: 0.5px;
        }
        
        .weight-row {
            display: flex;
            justify-content: space-around;
            margin-top: 4px;
            text-align: center;
        }
        
        .weight-box {
            border: 1px solid #000;
            padding: 1px;
            width: 45%;
            min-height: 15mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .weight-label {
            font-weight: bold;
            font-size: 7px;
        }
        
        .weight-value {
            font-size: 9px;
            font-weight: bold;
            margin-top: 1px;
        }
        
        .size-box {
            border: 1px solid #000;
            padding: 2px;
            margin: 3px 0;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
        }
        
        .kenda-size {
            font-size: 7px;
            margin-top: 1px;
            color: #333;
            font-weight: normal;
        }
        
        .print-controls {
            text-align: center;
            margin: 10px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            max-width: 210mm;
            margin: 20px auto;
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
        
        .label-counter {
            position: absolute;
            top: 1mm;
            right: 1mm;
            font-size: 7px;
            background: #f0f0f0;
            padding: 1px 3px;
            border-radius: 2px;
            z-index: 10;
        }
        
        .page-info {
            position: absolute;
            bottom: 1mm;
            right: 1mm;
            font-size: 6px;
            color: #999;
        }
        
        .print-date {
            position: absolute;
            bottom: 1mm;
            left: 1mm;
            font-size: 6px;
            color: #999;
        }
        
        /* Untuk tampilan preview di browser */
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
            
            .labels-container {
                max-width: 210mm;
                margin: 0 auto;
            }
        }
        
        /* Optional: Border untuk memudahkan cutting */
        .cutting-line {
            position: absolute;
            border: 1px dashed #ccc;
        }
        
        .cutting-line.top {
            top: 0;
            left: 5mm;
            right: 5mm;
            height: 0;
        }
        
        .cutting-line.bottom {
            bottom: 0;
            left: 5mm;
            right: 5mm;
            height: 0;
        }
        
        .cutting-line.left {
            left: 0;
            top: 5mm;
            bottom: 5mm;
            width: 0;
        }
        
        .cutting-line.right {
            right: 0;
            top: 5mm;
            bottom: 5mm;
            width: 0;
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Semua Label (<?php echo count($labels_data); ?> halaman)
        </button>
        <button onclick="window.close()" class="btn-close">
            <i class="fas fa-times"></i> Tutup
        </button>
        <div style="margin-top: 10px; color: #666; font-size: 14px;">
            <strong>Format:</strong> XDS BICYCLE CAMBODIA | 
            <strong>Ukuran:</strong> A6 Portrait (105mm × 148mm) | 
            <strong>Total:</strong> <?php echo count($labels_data); ?> label | 
            <strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($print_time)); ?>
        </div>
        <div style="margin-top: 5px; color: #888; font-size: 12px;">
            <small><i class="fas fa-info-circle"></i> 1 label per halaman - Siap untuk cetak label printer atau kertas A6</small>
        </div>
    </div>

    <!-- Container untuk semua label -->
    <div class="labels-container">
        <?php foreach($labels_data as $index => $label_data): ?>
        <!-- Setiap label dalam halaman A6 terpisah -->
        <div class="a6-page">
            <div class="label-container">
                <!-- Cutting lines (optional) -->
                <div class="cutting-line top"></div>
                <div class="cutting-line bottom"></div>
                <div class="cutting-line left"></div>
                <div class="cutting-line right"></div>
                
                <!-- Label counter -->
                <div class="label-counter">
                    <?php echo $index + 1; ?>/<?php echo count($labels_data); ?>
                </div>
                
                <!-- Company Name -->
                <div class="company-name">
                    <?php echo $label_data['company_name'] ?? 'XDS BICYCLE CAMBODIA'; ?>
                </div>
                
                <!-- Product Name -->
                <div class="product-name">
                    <?php echo $label_data['product_name'] ?? 'BICYCLE TIRE'; ?>
                </div>
                
                <!-- Made In -->
                <div class="made-in">
                    <?php echo $label_data['made_in'] ?? 'MADE IN INDONESIA'; ?>
                </div>
                
                <!-- PO Number -->
                <div class="label-row">
                    <span class="label-field">PO#:</span>
                    <span class="label-value"><?php echo $label_data['po_no'] ?? '0-88130-7-1-5'; ?></span>
                </div>
                
                <!-- Size Information -->
                <div class="size-box">
                    SIZE : <?php echo $label_data['size'] ?? '650B*50 GRO COMP'; ?>
                    <div class="kenda-size">
                        KENDA SIZE: <?php echo $label_data['kenda_size'] ?? '650B*50 TR040 BK/BSK 60TPI R6275*2'; ?>
                    </div>
                </div>
                
                <!-- Item Number -->
                <div class="label-row">
                    <span class="label-field">ITEM NO :</span>
                    <span class="label-value"><?php echo $label_data['item_no'] ?? '1110-18373'; ?></span>
                </div>
                
                <!-- Package Number -->
                <div class="label-row">
                    <span class="label-field">P'KG NO :</span>
                    <span class="label-value"><?php 
                        echo !empty($label_data['pkg_no']) ? $label_data['pkg_no'] : 
                             (!empty($label_data['packing']['no_packing']) ? $label_data['packing']['no_packing'] : 
                             (!empty($label_data['label']['label_code']) ? $label_data['label']['label_code'] : ''));
                    ?></span>
                </div>
                
                <!-- Quantity -->
                <div class="label-row">
                    <span class="label-field">QTY.:</span>
                    <span class="label-value">
                        <?php 
                        $qty = $label_data['qty'] ?? 
                               ($label_data['packing']['jumlah_item'] ?? 25);
                        echo $qty; 
                        ?> PCS
                    </span>
                </div>
                
                <!-- Weight Information -->
                <div class="weight-row">
                    <div class="weight-box">
                        <div class="weight-label">N.W. (KGS)</div>
                        <div class="weight-value"><?php echo $label_data['nw'] ?? '19.88'; ?></div>
                    </div>
                    <div class="weight-box">
                        <div class="weight-label">G.W. (KGS)</div>
                        <div class="weight-value"><?php echo $label_data['gw'] ?? '26.25'; ?></div>
                    </div>
                </div>
                
                <!-- Barcode -->
                <div class="barcode-container">
                    <div class="barcode">*<?php echo $label_data['label']['label_code']; ?>*</div>
                    <div class="label-code"><?php echo $label_data['label']['label_code']; ?></div>
                </div>
                
                <!-- Bale Information (jika ada) -->
                <?php if(!empty($label_data['label']['bale_number']) && $label_data['label']['bale_number'] > 0): ?>
                <div class="label-row">
                    <span class="label-field">BALE NO:</span>
                    <span class="label-value"><?php echo $label_data['label']['bale_number']; ?> OF <?php echo $label_data['label']['total_bales']; ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Customer Info (jika ada) -->
                <?php if(!empty($label_data['packing']['customer'])): ?>
                <div class="label-row" style="margin-top: 2px;">
                    <span class="label-field">CUSTOMER:</span>
                    <span class="label-value" style="font-size: 8px;"><?php echo $label_data['packing']['customer']; ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Print Date -->
                <div class="print-date">
                    <?php echo date('d/m/Y', strtotime($print_time)); ?>
                </div>
                
                <!-- Page Info -->
                <div class="page-info">
                    Page <?php echo $index + 1; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

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
        
        // Untuk preview di browser, hitung tinggi halaman
        window.addEventListener('load', function() {
            const pages = document.querySelectorAll('.a6-page');
            pages.forEach((page, index) => {
                // Tambahkan class page-break untuk semua kecuali halaman terakhir
                if (index < pages.length - 1) {
                    page.classList.add('page-break');
                }
            });
            
            console.log(`Total ${pages.length} halaman A6 siap dicetak`);
        });
    </script>
</body>
</html>
