<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Packing List - <?php echo $packing['no_packing'] ?? 'No Packing'; ?></title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
            @page { margin: 0.5cm; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            color: #666;
            font-style: italic;
        }
        
        .document-title {
            font-size: 22px;
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .document-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin: 50px 0 10px 0;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-tube {
            background: #007bff;
            color: white;
        }
        
        .badge-tire {
            background: #28a745;
            color: white;
        }
        
        .print-controls {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-print:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Dokumen
        </button>
        <button class="btn-print" onclick="window.close()" style="background: #6c757d; margin-left: 10px;">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>
    
    <div class="print-container">
        <div class="header">
            <div class="company-name">GUDANG KENDA</div>
            <div class="company-tagline">Designed for Your Journey</div>
            <div class="document-title">PACKING LIST</div>
        </div>
        
        <div class="document-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">No. Packing:</span>
                    <span class="info-value"><?php echo $packing['no_packing']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal:</span>
                    <span class="info-value"><?php echo date('d/m/Y', strtotime($packing['tanggal'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Customer:</span>
                    <span class="info-value"><?php echo $packing['customer']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Alamat:</span>
                    <span class="info-value"><?php echo $packing['alamat']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Label:</span>
                    <span class="info-value badge <?php echo $packing['status_scan_out'] == 'Tercetak' ? 'badge-success' : 'badge-warning'; ?>">
                        <?php echo $packing['status_scan_out']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Loading:</span>
                    <span class="info-value badge <?php echo $packing['status_scan_in'] == 'Selesai' ? 'badge-success' : 'badge-warning'; ?>">
                        <?php echo $packing['status_scan_in']; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <?php if (isset($packing['items']) && !empty($packing['items'])): ?>
        <h3>Detail Items</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_qty = 0; ?>
                <?php foreach ($packing['items'] as $index => $item): ?>
                <?php $total_qty += $item['qty']; ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $item['kode_barang']; ?></td>
                    <td><?php echo $item['nama_barang']; ?></td>
                    <td>
                        <span class="badge <?php echo $item['kategori'] == 'Tube' ? 'badge-tube' : 'badge-tire'; ?>">
                            <?php echo $item['kategori']; ?>
                        </span>
                    </td>
                    <td><?php echo $item['qty']; ?></td>
                    <td><?php echo $item['keterangan'] ?? '-'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Total:</td>
                    <td colspan="2" style="font-weight: bold;"><?php echo $total_qty; ?> Unit</td>
                </tr>
            </tfoot>
        </table>
        <?php endif; ?>
        
        <div class="signature-section">
            <div class="signature-box">
                <div>Packing Oleh:</div>
                <div class="signature-line"></div>
                <div>(____________________)</div>
                <div>Tanggal: <?php echo date('d/m/Y'); ?></div>
            </div>
            
            <div class="signature-box">
                <div>Diperiksa Oleh:</div>
                <div class="signature-line"></div>
                <div>(____________________)</div>
                <div>Tanggal: <?php echo date('d/m/Y'); ?></div>
            </div>
        </div>
        
        <div class="footer">
            <p>Dokumen ini dicetak secara elektronik dari Sistem Gudang KENDA</p>
            <p>Tanggal cetak: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
    
    <script>
        // Auto print jika diinginkan
        window.onload = function() {
            // Uncomment baris berikut untuk auto print
            // window.print();
        };
        
        // Setelah print, tutup window
        window.onafterprint = function() {
            // Uncomment baris berikut untuk auto close setelah print
            // setTimeout(function() { window.close(); }, 500);
        };
    </script>
</body>
</html>
