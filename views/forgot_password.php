<?php 
// File: views/forgot_password.php
require_once '../includes/functions.php';

// Cek jika user sudah login, redirect ke dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .forgot-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .forgot-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .forgot-header {
            background: #007bff;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .forgot-body {
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <i class="fas fa-key fa-3x mb-3"></i>
                <h3>Reset Password</h3>
                <p class="mb-0">Admin Gudang</p>
            </div>
            <div class="forgot-body">
                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                        </div>
                        <div class="form-text">Masukkan email Anda untuk menerima link reset password</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">Kembali ke Login</a>
                </div>
                
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>