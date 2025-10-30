<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = sanitize($_POST['token']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Password tidak cocok']);
        exit;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if token is valid and not expired
    $query = "SELECT id FROM users WHERE reset_token = :token AND token_expiry > NOW()";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        $query = "UPDATE users SET password = :password, reset_token = NULL, token_expiry = NULL WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user['id']);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password berhasil direset. Silakan login.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Token tidak valid atau sudah kedaluwarsa']);
    }
}
?>