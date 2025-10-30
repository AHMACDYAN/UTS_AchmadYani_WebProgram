<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check status
        if ($user['status'] != 'active') {
            echo json_encode(['success' => false, 'message' => 'Akun belum diaktivasi. Silakan cek email Anda.']);
            exit;
        }
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_status'] = $user['status'];
            
            echo json_encode([
                'success' => true, 
                'message' => 'Login berhasil!', 
                'redirect' => '../views/dashboard.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Password salah']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email tidak terdaftar']);
    }
}
?>