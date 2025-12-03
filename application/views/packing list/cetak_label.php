<!-- cetak_label.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label - <?php echo $packing->no_packing; ?></title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0.5cm;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
        }
        
        .label-container {
            border: 1px solid #000;
            padding: 15px;
            margin: 10px;
            width: 300px;
            height: 200px;
            display: inline-block;
            vertical-align: top;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .barcode {
            text-align: center;
            margin: 15px 0;
        }
        
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        
        .info-row {
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }
        
        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-print:hover {
            background: #c82333;
        }
    </style>
    <!-- Include library barcode jika diperlukan -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script> -->
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Label
    </button>
    
    <div class="label-container">
        <div class="header">
            <h2 style="margin: 0; font-size: 20px;">PACKING LIST</h2>
            <h3 style="margin: 5px 0; font-size: 18px;"><?php echo $packing->no_packing; ?></h3>
        </div>
        
        <div class="info-row">
            <span class="info-label">Customer:</span>
            <?php echo $packing->customer; ?>
        </div>
        
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <?php echo date('d/m/Y', strtotime($packing->tanggal)); ?>
        </div>
        
        <div class="info-row">
            <span class="info-label">Jumlah Item:</span>
            <?php echo $packing->jumlah_item; ?> item
        </div>
        
        <div class="barcode">
            <div style="font-size: 10px; margin-bottom: 5px;">SCAN BARCODE:</div>
            <!-- Barcode akan dirender di sini -->
            <svg id="barcode"></svg>
        </div>
        
        <div class="qr-code">
            <!-- QR Code akan ditampilkan di sini -->
            <div id="qrcode"></div>
        </div>
    </div>
    
    <script>
    // Auto print jika parameter autoprint ada
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('autoprint') === '1') {
        window.print();
    }
    
    // Generate barcode (contoh menggunakan JsBarcode)
    /*
    if (typeof JsBarcode !== 'undefined') {
        JsBarcode("#barcode", "<?php echo $packing->no_packing; ?>", {
            format: "CODE128",
            displayValue: true,
            fontSize: 12,
            height: 40,
            margin: 10
        });
    }
    */
    
    // Generate QR Code (contoh menggunakan QRCode.js)
    /*
    if (typeof QRCode !== 'undefined') {
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo base_url('packing/scan/') . $packing->id; ?>",
            width: 80,
            height: 80
        });
    }
    */
    </script>
</body>
</html>
