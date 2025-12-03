<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Cetak Label Packing List'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            
            .print-section, .print-section * {
                visibility: visible;
            }
            
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
        
        .label-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 10px;
        }
        
        @page {
            size: A4;
            margin: 0.5cm;
        }
        
        .label-card {
            border: 2px solid #000;
            border-radius: 5px;
            padding: 10px;
            height: 120px;
            font-size: 12px;
            position: relative;
            background: white;
            break-inside: avoid;
        }
        
        .label-header {
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .label-content {
            margin-bottom: 5px;
        }
        
        .label-content div {
            margin-bottom: 2px;
            line-height: 1.2;
        }
        
        .barcode-area {
            text-align: center;
            font-family: 'Libre Barcode 39', cursive;
            font-size: 24px;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        
        .label-footer {
            font-size: 10px;
            text-align: center;
            color: #666;
            margin-top: 5px;
            border-top: 1px dashed #ccc;
            padding-top: 3px;
        }
        
        /* Responsive */
        @media (min-width: 1200px) {
            .label-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .label-container {
                grid-template-columns: 1fr;
            }
        }
        
        /* Kenda brand colors */
        .btn-kenda {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        .btn-kenda:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white;
        }
        
        .text-kenda-red {
            color: #dc3545;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Control Panel -->
    <div class="no-print container-fluid bg-light py-3 mb-3 shadow">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="mb-0">
                    <i class="fas fa-print me-2"></i>Cetak Label Packing List
                </h4>
                <p class="text-muted mb-0">
                    Jumlah: 
                    <span class="badge bg-primary">
                        <?php echo isset($total_labels) ? $total_labels : 0; ?> label
                    </span>
                    <?php if (isset($print_date)): ?>
                        <span class="ms-2">
                            <i class="fas fa-calendar me-1"></i><?php echo $print_date; ?>
                        </span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-kenda me-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
                <button class="btn btn-secondary" onclick="window.close()">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <div class="form-check form-switch mt-2 d-inline-block ms-3">
                    <input class="form-check-input" type="checkbox" id="autoPrintCheck" checked>
                    <label class="form-check-label" for="autoPrintCheck">Auto Print</label>
                </div>
            </div>
        </div>
        
        <?php if (isset($error_message) && !empty($error_message)): ?>
        <div class="row mt-2">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
       
    </div>
    
    <!-- Print Area -->
    <div class="print-section">
        <?php if (isset($packing_lists) && is_array($packing_lists) && count($packing_lists) > 0): ?>
            <div class="label-container">
                <?php 
                $counter = 0;
                foreach ($packing_lists as $packing): 
                    $counter++;
                    
                    // Format tanggal
                    $tanggal = isset($packing->tanggal) ? date('d/m/Y', strtotime($packing->tanggal)) : date('d/m/Y');
                    
                    // Format jumlah item
                    $jumlah_item = isset($packing->jumlah_item) ? intval($packing->jumlah_item) : 0;
                    
                    // No. Packing
                    $no_packing = isset($packing->no_packing) ? htmlspecialchars($packing->no_packing) : 'N/A';
                    
                    // Customer
                    $customer = isset($packing->customer) ? htmlspecialchars($packing->customer) : 'N/A';
                    // Potong teks jika terlalu panjang
                    if (strlen($customer) > 20) {
                        $customer = substr($customer, 0, 17) . '...';
                    }
                ?>
                    <div class="label-card">
                        <div class="label-header">
                            PACKING LIST
                        </div>
                        <div class="label-content">
                            <div><strong>NO:</strong> <?php echo $no_packing; ?></div>
                            <div><strong>TGL:</strong> <?php echo $tanggal; ?></div>
                            <div><strong>CUST:</strong> <?php echo $customer; ?></div>
                            <div><strong>ITEM:</strong> <?php echo $jumlah_item; ?> pcs</div>
                        </div>
                        <div class="barcode-area">
                            *<?php echo $no_packing; ?>*
                        </div>
                        <div class="label-footer">
                            <?php echo date('Y-m-d'); ?> | KENDA
                        </div>
                    </div>
                    
                    <?php 
                    // Add page break every 8 labels for better print layout
                    if ($counter % 8 === 0): 
                    ?>
                        <div class="page-break"></div>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="container text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h4>Tidak ada data packing list</h4>
                    <p class="mb-0">Tidak ada packing list yang ditemukan untuk dicetak.</p>
                    <?php if (isset($error_message)): ?>
                        <p class="text-danger mt-2">
                            <small><i class="fas fa-info-circle me-1"></i><?php echo $error_message; ?></small>
                        </p>
                    <?php endif; ?>
                </div>
                <button class="btn btn-kenda mt-3" onclick="window.history.back()">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </button>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check auto print setting
            const autoPrintCheck = document.getElementById('autoPrintCheck');
            const urlParams = new URLSearchParams(window.location.search);
            
            console.log('Auto print check:', autoPrintCheck.checked);
            console.log('URL params:', urlParams.toString());
            
            // Jika auto print diaktifkan dan ada parameter autoprint=1
            if (autoPrintCheck.checked && urlParams.get('autoprint') === '1') {
                console.log('Auto print triggered');
                setTimeout(function() {
                    window.print();
                    // Setelah print, update status di parent window
                    if (window.opener && !window.opener.closed) {
                        try {
                            window.opener.postMessage({
                                type: 'print_completed',
                                timestamp: new Date().toISOString()
                            }, '*');
                        } catch (e) {
                            console.log('Cannot communicate with opener:', e);
                        }
                    }
                }, 1500);
            }
            
            // Handle after print
            window.onafterprint = function(event) {
                console.log('Print completed at:', new Date().toISOString());
                // Optional: Auto close after print
                // setTimeout(function() {
                //     window.close();
                // }, 1000);
            };
            
            // Handle print dialog cancel
            window.addEventListener('beforeprint', function(event) {
                console.log('Print dialog opened');
            });
        });
        
        // Utility function untuk URL parameter
        function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
        
        // Handle messages from parent window
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'trigger_print') {
                window.print();
            }
        });
    </script>
</body>
</html>
