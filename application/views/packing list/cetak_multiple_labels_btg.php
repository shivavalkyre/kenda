<!-- application/views/label/cetak_multiple_btg_a6.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Multiple Labels BTG</title>
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
            font-size: 8px;
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
        
        .company-name {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        
        .vendor-name {
            font-size: 9px;
            text-align: center;
            margin-bottom: 3px;
            font-weight: bold;
        }
        
        .product-name {
            font-size: 10px;
            text-align: center;
            margin: 4px 0;
            font-weight: bold;
            border: 1px solid #000;
            padding: 2px;
        }
        
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            padding: 0 2px;
        }
        
        .made-in {
            text-align: center;
            font-weight: bold;
            margin: 5px 0;
            font-size: 9px;
        }
        
        .import-address {
            font-size: 6px;
            margin-top: 5px;
            line-height: 1.1;
            border-top: 1px solid #000;
            padding-top: 2px;
            text-align: justify;
        }
        
        .cnpj {
            font-size: 7px;
            text-align: center;
            margin-top: 5px;
            font-weight: bold;
        }
        
        .weight-info {
            display: flex;
            justify-content: space-around;
            margin: 5px 0;
            text-align: center;
        }
        
        .weight-box {
            border: 1px solid #000;
            padding: 2px;
            width: 45%;
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
            <i class="fas fa-print"></i> Cetak <?php echo count($labels_data); ?> Label BTG
        </button>
        <button onclick="window.close()" class="btn-close">
            <i class="fas fa-times"></i> Tutup
        </button>
        <div style="margin-top: 10px; color: #666;">
            <small>Format: BTG PACTUAL COMMODITIES | Ukuran: A6 Portrait | Total: <?php echo count($labels_data); ?> label</small>
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
            
            <!-- Company Name -->
            <div class="company-name">
                <?php echo $label_data['company_name'] ?? 'BIG PACTUAL COMMODITIES SERTRADING S.A'; ?>
            </div>
            
            <!-- Vendor Name -->
            <div class="vendor-name">
                VENDOR: <?php echo $label_data['vendor_name'] ?? 'KENDA RUBBER'; ?>
            </div>
            
            <!-- PO Number & Código -->
            <div class="label-row">
                <span>PO#: <?php echo $label_data['po_no'] ?? '6586-4'; ?></span>
                <span>CÓDIGO: <?php echo $label_data['codigo'] ?? '59338'; ?></span>
            </div>
            
            <!-- Product Name -->
            <div class="product-name">
                <?php echo $label_data['product_name'] ?? 'PNEU PUBLICICLETA'; ?>
            </div>
            
            <!-- Size & Color -->
            <div class="label-row">
                <span>SIZE: <?php echo $label_data['size'] ?? '26*1.95 K1300 BK'; ?></span>
                <span>COLOR: <?php echo $label_data['color'] ?? '26*1.95 K1300 PRETO'; ?></span>
            </div>
            
            <!-- Quantity -->
            <div class="label-row">
                <span>QTY: <?php echo $label_data['qty'] ?? 25; ?> PCS</span>
            </div>
            
            <!-- Weight Information -->
            <div class="weight-info">
                <div class="weight-box">
                    N.W.<br>
                    <strong><?php echo $label_data['nw'] ?? '18.75'; ?> KGS</strong>
                </div>
                <div class="weight-box">
                    G.W.<br>
                    <strong><?php echo $label_data['gw'] ?? '18.95'; ?> KGS</strong>
                </div>
            </div>
            
            <!-- Made In -->
            <div class="made-in">
                <?php echo $label_data['made_in'] ?? 'MADE IN INDONESIA'; ?>
            </div>
            
            <!-- Import Address -->
            <div class="import-address">
                <?php echo $label_data['import_address'] ?? 'BTG PACTUAL COMMODITIES SERTRADING S.A ROD. GOVERNADOR MARIO COVAS 3101, KM 282. AREA 4,QUADRA 2; PADRE MATHIAS – CARIACICA – ZIP CODE: 29157-100 – ESPIRITO SANTO/ES.BRAZIL'; ?>
            </div>
            
            <!-- CNPJ -->
            <div class="cnpj">
                CNPJ: <?php echo $label_data['cnpj'] ?? '04.626.426/0007-00'; ?>
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
            <div class="label-row" style="position: absolute; top: 1mm; left: 1mm; font-size: 7px;">
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
