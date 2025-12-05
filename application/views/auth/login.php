<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Gudang KENDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --kenda-black: #000000;
            --kenda-red: #ff0000;
            --kenda-white: #ffffff;
            --kenda-dark-gray: #333333;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        
        .background-slideshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .slide.active {
            opacity: 1;
        }
        
        /* Overlay untuk membuat background lebih gelap */
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }
        
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            position: relative;
            border-radius: 0 !important;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--kenda-black) 0%, var(--kenda-dark-gray) 100%);
            color: var(--kenda-white);
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid var(--kenda-red);
            position: relative;
        }
        
        .login-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--kenda-red);
        }
        
        .login-body {
            padding: 40px 30px;
            background: transparent;
        }
        
        .btn-kenda-login {
            background: linear-gradient(135deg, var(--kenda-black) 0%, var(--kenda-dark-gray) 100%);
            color: var(--kenda-white);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 0 !important;
            transition: all 0.3s ease;
        }
        
        .btn-kenda-login:hover {
            background: linear-gradient(135deg, var(--kenda-red) 0%, #cc0000 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,0,0,0.3);
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 0 !important;
            padding: 12px 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .form-control:focus {
            border-color: var(--kenda-red);
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
            background: var(--kenda-white);
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .kenda-logo {
            max-width: 200px;
            height: auto;
        }
        
        .tagline {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .sub-tagline {
            font-size: 0.8rem;
            opacity: 0.7;
            font-style: italic;
        }
        
        .input-group-text {
            background-color: var(--kenda-black);
            color: var(--kenda-white);
            border: 2px solid var(--kenda-black);
            border-radius: 0 !important;
        }
        
        .alert-danger {
            background-color: rgba(255, 0, 0, 0.1);
            border: 1px solid var(--kenda-red);
            color: #cc0000;
            border-radius: 0 !important;
        }
        
        .form-check-input {
            border-radius: 0 !important;
        }
        
        .form-check-input:checked {
            background-color: var(--kenda-red);
            border-color: var(--kenda-red);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        /* Slide indicator */
        .slide-indicator {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        
        .indicator-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .indicator-dot.active {
            background: var(--kenda-red);
            transform: scale(1.2);
        }
        
        /* Loading animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-card {
            animation: fadeIn 0.8s ease-out;
        }
        
        /* Hapus outline pada card ketika focus */
        .login-card:focus {
            outline: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        /* Fallback jika logo tidak load */
        .logo-fallback {
            font-weight: bold;
            font-size: 2.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--kenda-white);
        }
    </style>
</head>
<body>
    <!-- Background Slideshow -->
    <div class="background-slideshow">
        <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/login-bg/bg1.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/login-bg/bg2.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/login-bg/bg3.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/login-bg/bg4.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/login-bg/bg5.png'); ?>');"></div>
    </div>
    
    <!-- Overlay untuk darken background -->
    <div class="background-overlay"></div>
    
    <!-- Slide Indicator -->
    <div class="slide-indicator">
        <div class="indicator-dot active" data-slide="0"></div>
        <div class="indicator-dot" data-slide="1"></div>
        <div class="indicator-dot" data-slide="2"></div>
        <div class="indicator-dot" data-slide="3"></div>
        <div class="indicator-dot" data-slide="4"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="<?php echo base_url('assets/images/logo/kenda-logo1.png'); ?>" 
                         alt="KENDA Logo" 
                         class="kenda-logo"
                         onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='block';">
                    <div id="logo-fallback" class="logo-fallback" style="display: none;">KENDA</div>
                </div>
                <div class="tagline">Sistem Informasi Gudang</div>
                <div class="sub-tagline">Designed for Your Journey</div>
            </div>
            
            <div class="login-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo site_url('auth/process-login'); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username" required 
                                   placeholder="Masukkan username Anda">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required 
                                   placeholder="Masukkan password Anda">
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    
                    <button type="submit" class="btn btn-kenda-login w-100 py-3">
                        <i class="fas fa-sign-in-alt me-2"></i>LOGIN
                    </button>
                </form>
                
                <div class="login-footer">
                    <small class="text-muted">
                        &copy; 2024 KENDA - Sistem Informasi Gudang Internal
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
            
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.indicator-dot');
            let currentSlide = 0;
            const slideInterval = 5000;
            
            function showSlide(n) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }
            
            function nextSlide() {
                showSlide(currentSlide + 1);
            }
            
            let slideTimer = setInterval(nextSlide, slideInterval);
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    clearInterval(slideTimer);
                    showSlide(index);
                    slideTimer = setInterval(nextSlide, slideInterval);
                });
            });
            
            const loginCard = document.querySelector('.login-card');
            loginCard.addEventListener('mouseenter', () => {
                clearInterval(slideTimer);
            });
            
            loginCard.addEventListener('mouseleave', () => {
                slideTimer = setInterval(nextSlide, slideInterval);
            });
            
            loginCard.style.transform = 'translateY(20px)';
            loginCard.style.opacity = '0';
            
            setTimeout(() => {
                loginCard.style.transition = 'all 0.8s ease';
                loginCard.style.transform = 'translateY(0)';
                loginCard.style.opacity = '1';
            }, 100);
            
            function preloadImages() {
                const imageUrls = [
                    '<?php echo base_url('assets/images/login-bg/bg1.png'); ?>',
                    '<?php echo base_url('assets/images/login-bg/bg2.png'); ?>',
                    '<?php echo base_url('assets/images/login-bg/bg3.png'); ?>',
                    '<?php echo base_url('assets/images/login-bg/bg4.png'); ?>',
                    '<?php echo base_url('assets/images/login-bg/bg5.png'); ?>',
                    '<?php echo base_url('assets/images/logo/kenda-logo1.png'); ?>'
                ];
                
                imageUrls.forEach(url => {
                    const img = new Image();
                    img.src = url;
                });
            }
            
            preloadImages();
            
            loginCard.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
