<?php
// File: includes/header.php
require_once 'functions.php';

// Dapatkan data user dari session (tanpa Auth class)
$current_user = null;
if (isLoggedIn()) {
    $current_user = [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'full_name' => $_SESSION['user_name']
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        if (isset($page_title)) {
            echo $page_title . " - Admin Gudang";
        } else {
            echo "Admin Gudang";
        }
        ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .user-dropdown {
            cursor: pointer;
        }
        .sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
            padding: 20px 0;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar .nav-link:hover {
            background-color: #007bff;
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <?php if (isLoggedIn() && $current_user): ?>
    <!-- Navigation for authenticated users -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-warehouse me-2"></i>Admin Gudang
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-dropdown" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($current_user['full_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user-edit me-2"></i>Edit Profil
                            </a></li>
                            <li><a class="dropdown-item" href="profile.php?tab=password">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../processes/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar and Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                               href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" 
                               href="products.php">
                                <i class="fas fa-boxes me-2"></i>Manajemen Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" 
                               href="profile.php">
                                <i class="fas fa-user-cog me-2"></i>Profil Pengguna
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 main-content">
    <?php else: ?>
    <!-- Simple header for non-authenticated users -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-warehouse me-2"></i>Admin Gudang
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="login.php">Login</a>
                <a class="nav-link" href="register.php">Daftar</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>