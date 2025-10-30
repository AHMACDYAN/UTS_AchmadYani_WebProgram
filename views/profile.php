<?php
require_once '../includes/header.php';

// Cek login
if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

$tab = $_GET['tab'] ?? 'profile';

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        
        $query = "UPDATE users SET full_name = :full_name, email = :email WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $full_name;
            $_SESSION['user_email'] = $email;
            $message = 'Profil berhasil diperbarui!';
            $message_type = 'success';
        } else {
            $message = 'Gagal memperbarui profil. Silakan coba lagi.';
            $message_type = 'danger';
        }
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Get current password from database
        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($new_password !== $confirm_password) {
            $message = 'Password baru tidak cocok!';
            $message_type = 'danger';
        } elseif (strlen($new_password) < 6) {
            $message = 'Password baru minimal 6 karakter!';
            $message_type = 'danger';
        } elseif (!password_verify($current_password, $user['password'])) {
            $message = 'Password saat ini salah!';
            $message_type = 'danger';
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':id', $user_id);
            
            if ($stmt->execute()) {
                $message = 'Password berhasil diubah!';
                $message_type = 'success';
            } else {
                $message = 'Gagal mengubah password. Silakan coba lagi.';
                $message_type = 'danger';
            }
        }
    }
}

// Get user data for display
$database = new Database();
$db = $database->getConnection();
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$current_user_data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Profil Pengguna</h2>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo $tab == 'profile' ? 'active' : ''; ?>" 
                       href="?tab=profile">Edit Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $tab == 'password' ? 'active' : ''; ?>" 
                       href="?tab=password">Ubah Password</a>
                </li>
            </ul>
            
            <!-- Message Alert -->
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <?php if ($tab == 'profile'): ?>
                    <!-- Edit Profile Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?php echo htmlspecialchars($current_user_data['full_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($current_user_data['email']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Status Akun</label>
                                        <input type="text" class="form-control" value="<?php echo ucfirst($current_user_data['status']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Terdaftar Sejak</label>
                                        <input type="text" class="form-control" value="<?php echo date('d F Y', strtotime($current_user_data['created_at'])); ?>" readonly>
                                    </div>
                                </div>
                                
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <?php elseif ($tab == 'password'): ?>
                    <!-- Change Password Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ubah Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="current_password" class="form-label">Password Saat Ini</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="new_password" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <div class="form-text">Minimal 6 karakter</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                                
                                <button type="submit" name="change_password" class="btn btn-primary">
                                    <i class="fas fa-key me-2"></i>Ubah Password
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-4">
                    <!-- User Info Card -->
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-5x text-primary"></i>
                            </div>
                            <h4><?php echo htmlspecialchars($current_user_data['full_name']); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($current_user_data['email']); ?></p>
                            <div class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                <?php echo ucfirst($current_user_data['status']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>