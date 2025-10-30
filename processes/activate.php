<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE users SET status = 'active', activation_token = NULL 
              WHERE activation_token = :token AND status = 'pending'";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':token', $token);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $message = "Akun berhasil diaktivasi! <a href='../views/login.php'>Login di sini</a>";
    } else {
        $message = "Token aktivasi tidak valid atau sudah kedaluwarsa";
    }
} else {
    $message = "Token tidak ditemukan";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aktivasi Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>Aktivasi Akun</h3>
                        <p><?php echo $message; ?></p>
                        <a href="../views/login.php" class="btn btn-primary">Ke Halaman Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>