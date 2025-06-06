<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiADU - Sistem Pengaduan Masyarakat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-custom:hover {
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-comments me-2"></i>SiADU
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                <a class="nav-link" href="{{ route('register') }}">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Suara Anda, Perubahan Nyata</h1>
            <p class="lead mb-5">Platform pengaduan masyarakat yang transparan, efisien, dan akuntabel. Sampaikan keluhan, aspirasi, dan laporan Anda dengan mudah.</p>
            <a href="{{ route('register') }}" class="btn-custom">Mulai Mengadu</a>
            <a href="{{ route('login') }}" class="btn-custom">Masuk</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="feature-card text-center">
                        <i class="fas fa-comments fa-3x text-primary mb-3"></i>
                        <h5>Mudah Digunakan</h5>
                        <p>Interface yang intuitif memudahkan masyarakat menyampaikan pengaduan</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card text-center">
                        <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                        <h5>Transparan</h5>
                        <p>Pantau status pengaduan Anda secara real-time dengan sistem tracking</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card text-center">
                        <i class="fas fa-users fa-3x text-info mb-3"></i>
                        <h5>Partisipatif</h5>
                        <p>Masyarakat dapat berinteraksi dan memberikan dukungan pada laporan</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card text-center">
                        <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                        <h5>Akuntabel</h5>
                        <p>Setiap pengaduan tercatat dan ditindaklanjuti oleh pihak berwenang</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p>&copy; 2025 SiADU - Sistem Pengaduan Masyarakat. Dikembangkan oleh Tim SI-47-04.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
