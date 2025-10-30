<?php
// Debug path
// echo "Current file: " . __FILE__ . "<br>";
// echo "Current dir: " . __DIR__ . "<br>";

$functions_path = __DIR__ . '/includes/functions.php';
if (!file_exists($functions_path)) {
    die("Functions file not found: " . $functions_path);
}

require_once $functions_path;

// Jika user sudah login, redirect ke dashboard
if (isLoggedIn()) {
    redirect('views/dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Pengguna - Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .feature-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .cta-buttons {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <strong>Admin Gudang</strong>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="views/login.php">Login</a>
                <a class="nav-link" href="views/register.php">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 mb-4">Sistem Manajemen Pengguna</h1>
            <p class="lead mb-4">Kelola data produk dan inventory gudang Anda dengan mudah dan efisien</p>
            <div class="cta-buttons">
                <a href="views/register.php" class="btn btn-light btn-lg me-3">Mulai Sekarang</a>
                <a href="views/login.php" class="btn btn-outline-light btn-lg">Login</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2>Fitur Utama Sistem</h2>
                    <p class="lead">Semua yang Anda butuhkan untuk mengelola gudang</p>
                </div>
            </div>
            
            <div class="row">
                <!-- Fitur 1 -->
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3" style="font-size: 3rem;">👤</div>
                            <h5 class="card-title">Manajemen Pengguna</h5>
                            <p class="card-text">
                                Sistem registrasi dan login yang aman dengan verifikasi email 
                                dan fitur lupa password
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Fitur 2 -->
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3" style="font-size: 3rem;">📦</div>
                            <h5 class="card-title">Manajemen Produk</h5>
                            <p class="card-text">
                                Kelola data produk dengan operasi CRUD lengkap: 
                                Tambah, Edit, Hapus, dan Lihat produk
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Fitur 3 -->
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3" style="font-size: 3rem;">🔒</div>
                            <h5 class="card-title">Keamanan</h5>
                            <p class="card-text">
                                Sistem keamanan terjamin dengan password hashing, 
                                session management, dan activation token
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 Sistem Manajemen Pengguna - Admin Gudang. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>