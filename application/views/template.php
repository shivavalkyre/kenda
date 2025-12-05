<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Sistem Informasi Gudang - KENDA'; ?></title>
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/images/logo/icon.png') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Load ECharts Library -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <style>

		/* DI AWAL CSS template.php, tambahkan: */

        :root {
            --kenda-black: #000000;
            --kenda-red: #ff0000;
            --kenda-white: #ffffff;
            --kenda-dark-gray: #333333;
            --kenda-light-gray: #f5f5f5;

			/* Tambahkan z-index variables */
		--z-sidebar: 1000;
		--z-navbar: 1100;
		--z-sidebar-backdrop: 1040;
		--z-dropdown: 1200;
		--z-modal-backdrop: 1250;
		--z-modal: 1300;
		--z-tooltip: 1400;
		--z-toast: 1500;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--kenda-light-gray);
            color: var(--kenda-black);
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        
        .wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        /* NAVBAR STYLING - PERBAIKAN DROPDOWN MOBILE */
        .navbar-kenda {
            background-color: var(--kenda-black);
            border-bottom: 3px solid var(--kenda-red);
            padding: 8px 0;
            height: 60px;
            flex-shrink: 0;
            z-index: 1100;
            position: relative;
        }
        
        .navbar-brand {
            padding: 0;
        }
        
        .navbar-logo {
            height: 35px;
            width: auto;
            max-width: 120px;
        }
        
        .logo-fallback {
            font-weight: bold;
            font-size: 1.4rem;
            color: var(--kenda-white);
            text-transform: uppercase;
            display: none;
        }
        
        .user-info {
            color: var(--kenda-white) !important;
            font-size: 0.9rem;
            padding: 8px 12px;
            cursor: pointer;
        }
        
        .user-info:hover {
            color: var(--kenda-red) !important;
        }
        
        /* PERBAIKAN DROPDOWN MENU UNTUK MOBILE */
        .dropdown-menu {
            border: 1px solid var(--kenda-red);
            border-radius: 0 !important;
            min-width: 200px;
            z-index: 1200 !important;
        }
        
        .dropdown-item {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .dropdown-item:hover {
            background-color: var(--kenda-red);
            color: var(--kenda-white);
        }
        
        /* MOBILE SIDEBAR TOGGLE */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--kenda-white);
            font-size: 1.2rem;
            padding: 8px;
            margin-right: 10px;
            z-index: 1101;
        }
        
        /* MAIN CONTENT AREA - MOBILE FRIENDLY */
        .main-container {
            display: flex;
            flex: 1;
            height: calc(100vh - 130px);
            overflow: hidden;
        }
        
        /* SIDEBAR STYLING - MOBILE FRIENDLY */
        .sidebar {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            width: 280px;
            height: 100%;
            overflow-y: auto;
            flex-shrink: 0;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            border-bottom: 2px solid var(--kenda-red);
            padding: 15px;
            margin-bottom: 10px;
        }
        
        .kenda-text {
            color: var(--kenda-white);
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            margin: 0;
        }
        
        .sidebar-menu {
            gap: 1px;
            padding: 0 8px;
        }
        
        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 10px;
            color: #cccccc;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
            border-radius: 0;
            font-size: 0.9rem;
        }
        
        .sidebar-menu .nav-link:hover {
            color: var(--kenda-white);
            background-color: var(--kenda-dark-gray);
            border-left-color: var(--kenda-red);
        }
        
        .sidebar-menu .nav-link.active {
            color: var(--kenda-white);
            background-color: var(--kenda-red);
            border-left-color: var(--kenda-red);
        }
        
        .sidebar-menu .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--kenda-red);
            transition: all 0.3s ease;
        }
        
        .sidebar-menu .nav-link.active i,
        .sidebar-menu .nav-link:hover i {
            color: var(--kenda-white);
        }
        
        /* CONTENT AREA STYLING - MOBILE FRIENDLY */
        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }
        
        .content-wrapper {
            background-color: var(--kenda-white);
            padding: 20px;
            flex: 1;
            margin: 0;
            width: 100%;
            overflow-y: auto;
            height: 100%;
            -webkit-overflow-scrolling: touch;
        }
        
        /* FOOTER STYLING - MOBILE FRIENDLY */
        .footer-kenda {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            border-top: 3px solid var(--kenda-red);
            padding: 12px 0;
            height: 70px;
            flex-shrink: 0;
            z-index: 1000;
        }
        
        .footer-kenda .container-fluid {
            padding: 0 15px;
        }
        
        /* ==================== */
        /* DASHBOARD STYLES - STYLE ASLI DIPERTAHANKAN */
        /* ==================== */
        
        .welcome-section {
            position: relative;
            background: 
                linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('<?php echo base_url('assets/images/backgrounds/warehouse-bg.jpg'); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 30px 20px;
            margin-bottom: 20px;
            border-left: 5px solid var(--kenda-red);
            border-radius: 8px;
            overflow: hidden;
            min-height: 150px;
            display: flex;
            align-items: center;
        }
        
        .welcome-content {
            position: relative;
            z-index: 2;
            width: 100%;
        }
        
        .welcome-section h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--kenda-white);
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }
        
        .welcome-section h3 {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
            color: var(--kenda-white);
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.7);
        }
        
        .welcome-section p {
            font-size: 0.95rem;
            color: #f8f8f8;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
            margin-bottom: 0;
        }
        
        /* STATS GRID - STYLE ASLI DIPERTAHANKAN */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            padding: 20px 15px;
            border-top: 4px solid var(--kenda-red);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 6px;
            transition: transform 0.2s ease;
        }
        
        .stat-card:active {
            transform: scale(0.98);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--kenda-black);
            margin-bottom: 5px;
            line-height: 1;
        }
        
        .stat-label {
            color: var(--kenda-dark-gray);
            font-size: 0.85rem;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .stat-trend {
            font-size: 0.75rem;
            padding: 3px 8px;
            background-color: var(--kenda-light-gray);
            border-radius: 3px;
            display: inline-block;
        }
        
        .trend-up {
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .trend-down {
            color: var(--kenda-red);
            background-color: rgba(255, 0, 0, 0.1);
        }
        
        /* CHARTS AND ACTIVITIES - STYLE ASLI DIPERTAHANKAN */
        .charts-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .chart-card, .activity-card {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
            border-radius: 6px;
        }
        
        .section-title {
            color: var(--kenda-black);
            border-bottom: 2px solid var(--kenda-red);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-time {
            background-color: var(--kenda-red);
            color: white;
            padding: 4px 8px;
            margin-right: 12px;
            min-width: 55px;
            text-align: center;
            font-size: 0.75rem;
            border-radius: 3px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .activity-content {
            flex: 1;
            font-size: 0.9rem;
        }
        
        /* BUTTON STYLING - MOBILE FRIENDLY */
        .btn-kenda {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            border: 2px solid var(--kenda-black);
            padding: 10px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 0 !important;
            font-size: 0.9rem;
        }
        
        .btn-kenda:hover {
            background-color: var(--kenda-red);
            color: var(--kenda-white);
            border-color: var(--kenda-red);
        }
        
        .btn-kenda-red {
            background-color: var(--kenda-red);
            color: var(--kenda-white);
            border: 2px solid var(--kenda-red);
            border-radius: 0 !important;
            font-size: 0.9rem;
        }
        
        .btn-kenda-red:hover {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            border-color: var(--kenda-black);
        }
        
        /* TABLE STYLING - MOBILE FRIENDLY */
        .table-responsive {
            border-radius: 0;
        }
        
        .table th {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            border-bottom: 2px solid var(--kenda-red);
            font-size: 0.85rem;
            padding: 12px 8px;
        }
        
        .table td {
            padding: 10px 8px;
            font-size: 0.85rem;
            vertical-align: middle;
        }
        
        /* CARD STYLES - STYLE ASLI DIPERTAHANKAN */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background: white;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-footer {
            background-color: white;
            border-top: 1px solid #e0e0e0;
            padding: 15px 20px;
        }
        
        /* BADGE STYLES */
        .badge {
            font-size: 0.75em;
            font-weight: 500;
        }
        
        .bg-tube {
            background-color: #007bff !important;
        }
        
        .bg-tire {
            background-color: #28a745 !important;
        }
        
        /* LABEL STYLES */
        .label-barcode {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .scan-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .scan-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .scan-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .scan-loaded {
            background-color: #d4edda;
            color: #155724;
        }
        
        /* FORM STYLES */
        .form-control, .form-select {
            border-radius: 0;
            border: 1px solid #ddd;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--kenda-red);
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
        }
        
        /* MODAL STYLES */
        .modal-content {
            border-radius: 0;
            border: 2px solid var(--kenda-red);
        }
        
        .modal-header {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            border-bottom: 2px solid var(--kenda-red);
        }
        
        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        /* PAGINATION STYLES */
        .pagination .page-link {
            border-radius: 0;
            color: var(--kenda-black);
            border: 1px solid #ddd;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--kenda-red);
            border-color: var(--kenda-red);
            color: white;
        }
        
        .pagination .page-link:hover {
            background-color: var(--kenda-light-gray);
            border-color: #ddd;
        }
        
        /* MOBILE SPECIFIC STYLES - PERBAIKAN DROPDOWN */
        @media (max-width: 768px) {
            .navbar-kenda {
                height: 56px;
                padding: 6px 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                width: 280px;
                height: calc(100vh - 126px);
                transform: translateX(-100%);
                z-index: 1050;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 70px;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }
            
            .sidebar-backdrop.show {
                display: block;
            }
            
            /* PERBAIKAN UTAMA: Dropdown menu untuk mobile */
            .navbar-nav .dropdown-menu {
                position: fixed !important;
                top: 56px !important;
                right: 15px !important;
                left: auto !important;
                width: 200px !important;
                transform: none !important;
                margin-top: 0 !important;
                z-index: 1200 !important;
            }
            
            .dropdown-menu.show {
                display: block !important;
            }
            
            .main-container {
                height: calc(100vh - 126px);
            }
            
            .content-wrapper {
                padding: 15px;
            }
            
            .welcome-section {
                padding: 25px 15px;
                min-height: 130px;
                margin-bottom: 15px;
            }
            
            .welcome-section h1 {
                font-size: 1.5rem;
            }
            
            .welcome-section h3 {
                font-size: 1.1rem;
            }
            
            .welcome-section p {
                font-size: 0.9rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                margin-bottom: 20px;
            }
            
            .stat-card {
                padding: 15px 10px;
            }
            
            .stat-number {
                font-size: 1.6rem;
            }
            
            .stat-label {
                font-size: 0.8rem;
            }
            
            .stat-trend {
                font-size: 0.7rem;
            }
            
            .chart-card, .activity-card {
                padding: 15px;
            }
            
            .section-title {
                font-size: 1rem;
                margin-bottom: 15px;
            }
            
            .footer-kenda {
                height: 70px;
                padding: 10px 0;
            }
            
            .footer-kenda .col-md-6 {
                text-align: center !important;
                margin-bottom: 5px;
            }
            
            .footer-kenda span {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-wrapper {
                padding: 12px;
            }
            
            .welcome-section {
                padding: 20px 12px;
                min-height: 120px;
            }
            
            .welcome-section h1 {
                font-size: 1.3rem;
            }
            
            .welcome-section h3 {
                fontSize: 1rem;
            }
            
            .activity-item {
                padding: 10px 0;
            }
            
            .activity-time {
                min-width: 50px;
                font-size: 0.7rem;
                padding: 3px 6px;
            }
            
            .activity-content {
                font-size: 0.85rem;
            }
            
            /* Dropdown lebih kecil di perangkat sangat kecil */
            .navbar-nav .dropdown-menu {
                width: 180px !important;
                right: 10px !important;
            }
        }
        
        /* TOUCH FRIENDLY INTERACTIONS */
        @media (hover: none) and (pointer: coarse) {
            .sidebar-menu .nav-link:hover {
                color: #cccccc;
                background-color: transparent;
                border-left-color: transparent;
            }
            
            .sidebar-menu .nav-link:active {
                color: var(--kenda-white);
                background-color: var(--kenda-red);
                border-left-color: var(--kenda-red);
            }
            
            .btn-kenda:hover,
            .btn-kenda-red:hover {
                transform: none;
            }
            
            .stat-card:hover {
                transform: none;
            }
            
            /* Pastikan dropdown tetap bisa di-tap di mobile */
            .dropdown-toggle::after {
                margin-left: 0.5em;
            }
        }
        
        /* FIX UNTUK DROPDOWN BOOTSTRAP DI MOBILE */
        .navbar-nav .nav-item.dropdown {
            position: static;
        }
        
        @media (min-width: 769px) {
            .navbar-nav .dropdown-menu {
                position: absolute;
            }
        }

		/* Navbar */
.navbar-kenda {
    z-index: var(--z-navbar);
}

/* Sidebar */
.sidebar {
    z-index: var(--z-sidebar);
}

/* Sidebar backdrop */
.sidebar-backdrop {
    z-index: var(--z-sidebar-backdrop);
}

/* Dropdown menu */
.navbar-nav .dropdown-menu {
    z-index: var(--z-dropdown) !important;
}

/* Modal (tambahkan di template.php) */
.modal {
    z-index: var(--z-modal) !important;
}

.modal-backdrop {
    z-index: var(--z-modal-backdrop) !important;
}

/* Tooltip */
.tooltip {
    z-index: var(--z-tooltip) !important;
}

/* Barcode Font */
@font-face {
    font-family: 'Libre Barcode 128';
    src: url('https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap');
}

/* QR Code Styles */
.qr-code {
    width: 150px;
    height: 150px;
    background: white;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.qr-code::before {
    content: '';
    width: 120px;
    height: 120px;
    background: 
        linear-gradient(45deg, transparent 45%, #333 45%, #333 55%, transparent 55%),
        linear-gradient(-45deg, transparent 45%, #333 45%, #333 55%, transparent 55%),
        linear-gradient(45deg, #333 45%, transparent 45%, transparent 55%, #333 55%),
        linear-gradient(-45deg, #333 45%, transparent 45%, transparent 55%, #333 55%);
    background-size: 60px 60px;
    background-position: 0 0, 0 60px, 60px 0, 60px 60px;
}

/* Print Optimizations */
@media print {
    .print-optimize {
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    .avoid-break {
        page-break-inside: avoid;
        break-inside: avoid;
    }
}

    </style>
</head>
<body>
    <div class="wrapper">
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-kenda">
            <div class="container-fluid">
                <!-- Mobile Sidebar Toggle -->
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Logo Perusahaan -->
                <a class="navbar-brand d-flex align-items-center" href="<?php echo site_url('dashboard'); ?>">
                    <img src="<?php echo base_url('assets/images/logo/kenda-logo1.png'); ?>" 
                         alt="KENDA Logo" 
                         class="navbar-logo"
                         onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='block';">
                    <span id="logo-fallback" class="logo-fallback">KENDA</span>
                </a>
                
                <!-- User Info dan Logoff - PERBAIKAN STRUCTURE -->
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-info" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?php echo isset($username) ? $username : 'User'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?php echo site_url('auth/logout'); ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MOBILE SIDEBAR BACKDROP -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- MAIN CONTENT -->
        <div class="main-container">
            <!-- SIDEBAR -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <div class="kenda-text">MENU GUDANG</div>
                </div>

                <nav class="nav flex-column sidebar-menu">
                    <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'dashboard') ? 'active' : ''; ?>" href="<?php echo site_url('dashboard'); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
					                    <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'kategori') ? 'active' : ''; ?>" href="<?php echo site_url('kategori'); ?>">
                        <i class="fas fa-tags"></i>
                        <span>Kategori Barang</span>
                    </a>
                    <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'barang') ? 'active' : ''; ?>" href="<?php echo site_url('barang'); ?>">
                        <i class="fas fa-boxes"></i>
                        <span>Data Barang</span>
                    </a>
                    <!-- <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'masuk') ? 'active' : ''; ?>" href="<?php echo site_url('barang_masuk'); ?>">
                        <i class="fas fa-arrow-down"></i>
                        <span>Barang Masuk</span>
                    </a> -->
                    <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'packing') ? 'active' : ''; ?>" href="<?php echo site_url('packing_list'); ?>">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Packing List</span>
                    </a>
                    <!-- <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'keluar') ? 'active' : ''; ?>" href="<?php echo site_url('barang_keluar'); ?>">
                        <i class="fas fa-arrow-up"></i>
                        <span>Barang Keluar</span>
                    </a> -->
                    <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'scan') ? 'active' : ''; ?>" href="<?php echo site_url('scan'); ?>">
                        <i class="fas fa-qrcode"></i>
                        <span>Scan Label</span>
                    </a>
                    <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'stok') ? 'active' : ''; ?>" href="<?php echo site_url('stok'); ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan Stok</span>
                    </a>

                </nav>
            </div>
            
            <!-- CONTENT AREA -->
            <div class="content-area">
                <div class="content-wrapper">
                    <?php 
                    // Load konten dari file lain
                    if (isset($content)) {
                        $this->load->view($content);
                    } else {
                        echo '<div class="alert alert-warning">Konten tidak tersedia</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="footer-kenda">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span>&copy; 2024 KENDA - Sistem Informasi Gudang. All rights reserved.</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <span>Version 1.0 | Designed for Your Journey</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.href;
            const menuItems = document.querySelectorAll('.sidebar .nav-link');
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Active menu handling
            menuItems.forEach(item => {
                if(item.href === currentLocation) {
                    item.classList.add('active');
                }
            });

            // Mobile sidebar toggle
            if (sidebarToggle && sidebar && sidebarBackdrop) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarBackdrop.classList.toggle('show');
                });

                sidebarBackdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarBackdrop.classList.remove('show');
                });

                // Close sidebar when menu item is clicked on mobile
                menuItems.forEach(item => {
                    item.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('show');
                            sidebarBackdrop.classList.remove('show');
                        }
                    });
                });
            }

            // Handle logo error fallback
            const logo = document.querySelector('.navbar-logo');
            const fallback = document.getElementById('logo-fallback');
            
            if (logo && fallback) {
                logo.addEventListener('error', function() {
                    this.style.display = 'none';
                    fallback.style.display = 'block';
                });
            }

            // PERBAIKAN: Handle dropdown di mobile
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (dropdownToggle && dropdownMenu) {
                // Prevent closing when clicking inside dropdown
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Touch device optimizations
            if ('ontouchstart' in window) {
                document.body.classList.add('touch-device');
                
                // Improve dropdown touch experience
                const dropdownItems = document.querySelectorAll('.dropdown-item');
                dropdownItems.forEach(item => {
                    item.style.cursor = 'pointer';
                });
            }

            // PERBAIKAN: Handle window resize untuk dropdown
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const sidebarBackdrop = document.getElementById('sidebarBackdrop');
                const dropdownMenu = document.querySelector('.dropdown-menu');
                
                if (window.innerWidth > 768) {
                    if (sidebar) sidebar.classList.remove('show');
                    if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
                    
                    // Reset dropdown position untuk desktop
                    if (dropdownMenu) {
                        dropdownMenu.style.position = '';
                        dropdownMenu.style.top = '';
                        dropdownMenu.style.right = '';
                        dropdownMenu.style.left = '';
                        dropdownMenu.style.width = '';
                    }
                } else {
                    // Set dropdown position untuk mobile
                    if (dropdownMenu) {
                        dropdownMenu.style.position = 'fixed';
                        dropdownMenu.style.top = '56px';
                        dropdownMenu.style.right = '15px';
                        dropdownMenu.style.left = 'auto';
                        dropdownMenu.style.width = '200px';
                    }
                }
            });

            // Initialize dropdown position berdasarkan ukuran layar
            window.dispatchEvent(new Event('resize'));
        });

        // PERBAIKAN: Pastikan dropdown tertutup saat klik di luar
        document.addEventListener('click', function(e) {
            const dropdownMenu = document.querySelector('.dropdown-menu');
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            
            if (dropdownMenu && dropdownToggle && !dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                // Bootstrap akan menangani penutupan dropdown
            }
        });
    </script>
</body>
</html>
<?php
ob_end_flush();
?>
