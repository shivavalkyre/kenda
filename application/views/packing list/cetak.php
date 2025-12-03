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
            background-color: #f8f9fa;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #dc3545; /* Warna KENDA red */
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545; /* Warna KENDA red */
            margin-bottom: 5px;
        }
        
        .company-tagline {
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .document-title {
            font-size: 22px;
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
        }
        
        .document-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .info-value {
            color: #212529;
            font-size: 16px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
        }
        
        th {
            background-color: #dc3545; /* Warna KENDA red */
            color: white;
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }
        
        td {
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            padding-top: 30px;
            border-top: 2px solid #dee2e6;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin: 60px 0 10px 0;
            width: 100%;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .badge-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .badge-secondary {
            background: #6c757d;
            color: white;
        }
        
        .badge-primary {
            background: #007bff;
            color: white;
        }
        
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        
        .badge-tube {
            background: #007bff;
            color: white;
        }
        
        .badge-tire {
            background: #28a745;
            color: white;
        }
        
        .badge-dark {
            background: #343a40;
            color: white;
        }
        
        .print-controls {
            text-align: center;
            margin: 20px 0 40px 0;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .btn-print {
            background: #dc3545; /* Warna KENDA red */
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s;
            margin: 0 5px;
        }
        
        .btn-print:hover {
            background: #c82333;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e9ecef !important;
        }
        
        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0,0,0,0.1);
            z-index: -1;
            white-space: nowrap;
            font-weight: bold;
        }
        
        .company-logo {
            height: 60px;
            margin-bottom: 10px;
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="watermark">
        GUDANG KENDA
    </div>
    
    <div class="print-controls no-print">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Cetak Dokumen
        </button>
        <button class="btn-print btn-secondary" onclick="window.close()">
            <i class="fas fa-times me-2"></i> Tutup
        </button>
        <button class="btn-print" onclick="saveAsPDF()" style="background: #28a745;">
            <i class="fas fa-file-pdf me-2"></i> Simpan PDF
        </button>
    </div>
    
    <div class="print-container">
        <div class="header">
            <!-- Logo (jika ada) -->
            <!-- <img src="path/to/logo.png" alt="KENDA Logo" class="company-logo"> -->
            <div class="company-name">GUDANG KENDA</div>
            <div class="company-tagline">Sistem Manajemen Gudang Terintegrasi</div>
            <div class="document-title">PACKING LIST</div>
        </div>
        
        <div class="document-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">No. Packing List:</span>
                    <span class="info-value">
                        <strong><?php echo htmlspecialchars($packing['no_packing'] ?? 'N/A'); ?></strong>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Packing:</span>
                    <span class="info-value">
                        <?php 
                        if (!empty($packing['tanggal'])) {
                            echo date('d/m/Y', strtotime($packing['tanggal']));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">
                        <strong><?php echo htmlspecialchars($packing['customer'] ?? 'N/A'); ?></strong>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Alamat Pengiriman:</span>
                    <span class="info-value"><?php echo htmlspecialchars($packing['alamat'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Scan Out:</span>
                    <span class="info-value">
                        <?php
                        $statusScanOut = $packing['status_scan_out'] ?? 'printed';
                        $statusClass = 'badge-secondary';
                        $statusText = 'Unknown';
                        
                        switch($statusScanOut) {
                            case 'draft':
                                $statusClass = 'badge-secondary';
                                $statusText = 'Draft';
                                break;
                            case 'printed':
                                $statusClass = 'badge-warning';
                                $statusText = 'Label Tercetak';
                                break;
                            case 'scanned_out':
                                $statusClass = 'badge-success';
                                $statusText = 'Terkirim';
                                break;
                            default:
                                $statusClass = 'badge-secondary';
                                $statusText = $statusScanOut;
                        }
                        ?>
                        <span class="badge <?php echo $statusClass; ?>">
                            <i class="fas <?php 
                            echo $statusScanOut == 'printed' ? 'fa-print' : 
                                 ($statusScanOut == 'scanned_out' ? 'fa-check-circle' : 'fa-file'); 
                            ?> me-1"></i>
                            <?php echo $statusText; ?>
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Scan In:</span>
                    <span class="info-value">
                        <?php
                        $statusScanIn = $packing['status_scan_in'] ?? 'pending';
                        $statusClass = 'badge-secondary';
                        $statusText = 'Unknown';
                        
                        switch($statusScanIn) {
                            case 'pending':
                                $statusClass = 'badge-secondary';
                                $statusText = 'Belum Loading';
                                break;
                            case 'scanned_in':
                                $statusClass = 'badge-primary';
                                $statusText = 'Loading';
                                break;
                            case 'completed':
                                $statusClass = 'badge-success';
                                $statusText = 'Selesai';
                                break;
                            default:
                                $statusClass = 'badge-secondary';
                                $statusText = $statusScanIn;
                        }
                        ?>
                        <span class="badge <?php echo $statusClass; ?>">
                            <i class="fas <?php 
                            echo $statusScanIn == 'pending' ? 'fa-clock' : 
                                 ($statusScanIn == 'scanned_in' ? 'fa-truck-loading' : 'fa-flag-checkered'); 
                            ?> me-1"></i>
                            <?php echo $statusText; ?>
                        </span>
                    </span>
                </div>
                <?php if (!empty($packing['keterangan'])): ?>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">Keterangan:</span>
                    <span class="info-value"><?php echo htmlspecialchars($packing['keterangan']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($packing['items']) && is_array($packing['items']) && !empty($packing['items'])): ?>
        <h3 style="color: #dc3545; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="fas fa-boxes me-2"></i>Detail Items
        </h3>
        
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th width="100">Kategori</th>
                    <th width="80" class="text-center">Qty</th>
                    <th width="100" class="text-center">Satuan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_qty = 0;
                $no = 1;
                foreach ($packing['items'] as $item): 
                    $total_qty += isset($item['qty']) ? intval($item['qty']) : 0;
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td>
                        <span class="badge badge-dark">
                            <?php echo htmlspecialchars($item['kode_barang'] ?? $item['kode'] ?? 'N/A'); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($item['nama_barang'] ?? $item['nama'] ?? 'N/A'); ?></td>
                    <td class="text-center">
                        <?php
                        $kategori = $item['kategori'] ?? '';
                        $badgeClass = 'badge-secondary';
                        if ($kategori === 'Tube') {
                            $badgeClass = 'badge-tube';
                        } elseif ($kategori === 'Tire') {
                            $badgeClass = 'badge-tire';
                        }
                        ?>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo htmlspecialchars($kategori); ?>
                        </span>
                    </td>
                    <td class="text-center"><?php echo isset($item['qty']) ? number_format($item['qty']) : '0'; ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($item['satuan'] ?? 'Pcs'); ?></td>
                    <td><?php echo htmlspecialchars($item['keterangan'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-end" style="text-align: right; padding-right: 20px;">
                        <strong>TOTAL:</strong>
                    </td>
                    <td class="text-center">
                        <strong><?php echo number_format($total_qty); ?></strong>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <div class="row">
                <div class="col-6">
                    <strong>Jumlah Item:</strong> <?php echo number_format($packing['total_items'] ?? $total_qty); ?> item
                </div>
                <div class="col-6 text-end">
                    <strong>Total Quantity:</strong> <?php echo number_format($total_qty); ?> unit
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <div class="no-data">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <p>Tidak ada data item untuk packing list ini</p>
        </div>
        <?php endif; ?>
        
        <div class="signature-section">
            <div class="signature-box">
                <div style="font-weight: 600; margin-bottom: 10px;">Packing Oleh:</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px; font-style: italic;">(____________________)</div>
                <div style="margin-top: 5px; font-size: 12px;">
                    Nama & Tanda Tangan
                </div>
                <div style="margin-top: 15px; font-size: 12px;">
                    Tanggal: <?php echo date('d/m/Y'); ?>
                </div>
            </div>
            
            <div class="signature-box">
                <div style="font-weight: 600; margin-bottom: 10px;">Diperiksa Oleh:</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px; font-style: italic;">(____________________)</div>
                <div style="margin-top: 5px; font-size: 12px;">
                    QC Gudang
                </div>
                <div style="margin-top: 15px; font-size: 12px;">
                    Tanggal: <?php echo date('d/m/Y'); ?>
                </div>
            </div>
            
            <div class="signature-box">
                <div style="font-weight: 600; margin-bottom: 10px;">Diterima Oleh:</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px; font-style: italic;">(____________________)</div>
                <div style="margin-top: 5px; font-size: 12px;">
                    Customer / Pengirim
                </div>
                <div style="margin-top: 15px; font-size: 12px;">
                    Tanggal: _______________
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Dokumen ini dicetak secara elektronik dari Sistem Gudang KENDA</strong></p>
            <p>Tanggal cetak: <?php echo date('d/m/Y H:i:s'); ?></p>
            <p>IP Address: <?php echo $_SERVER['REMOTE_ADDR'] ?? 'N/A'; ?></p>
            <p style="font-size: 10px; color: #adb5bd; margin-top: 10px;">
                Dokumen ini sah dan berlaku sebagai bukti transaksi pengiriman barang
            </p>
        </div>
    </div>
    
    <script>
        // Auto print jika diinginkan
        window.onload = function() {
            // Uncomment baris berikut untuk auto print saat halaman terbuka
            // window.print();
            
            // Atau gunakan query string untuk auto print
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function() {
                    window.print();
                }, 1000);
            }
        };
        
        // Setelah print, tutup window jika auto print
        window.onafterprint = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function() {
                    window.close();
                }, 500);
            }
        };
        
        // Fungsi untuk save sebagai PDF (menggunakan html2pdf)
        function saveAsPDF() {
            alert('Fitur ekspor PDF memerlukan library html2pdf.js. Silakan install library tersebut.');
            
            // Jika sudah install html2pdf.js, gunakan kode berikut:
            /*
            const element = document.querySelector('.print-container');
            const opt = {
                margin:       10,
                filename:     'PackingList_<?php echo $packing['no_packing'] ?? 'document'; ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(opt).from(element).save();
            */
        }
        
        // Fungsi untuk preview sebelum print
        function printPreview() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print Preview</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">');
            printWindow.document.write('<style>' + document.querySelector('style').innerHTML + '</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(document.querySelector('.print-container').innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
        }
        
        // Shortcut keyboard
        document.addEventListener('keydown', function(e) {
            // Ctrl + P untuk print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // Esc untuk close
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>
    
    <!-- html2pdf library (uncomment jika sudah diinstall) -->
    <!--
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    -->
</body>
</html>
