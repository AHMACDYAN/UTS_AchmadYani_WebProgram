<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $full_name = sanitize($_POST['full_name']);

    $database = new Database();
    $db = $database->getConnection();

    // Check if email exists
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar']);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $activation_token = generateToken();

    // Insert user
    $query = "INSERT INTO users (email, password, full_name, activation_token) 
              VALUES (:email, :password, :full_name, :activation_token)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':activation_token', $activation_token);

    if ($stmt->execute()) {
        // Send activation email
        $emailSent = sendActivationEmail($email, $full_name, $activation_token);
        
        $response = [
            'success' => true, 
            'message' => 'Registrasi berhasil!',
            'activation_link' => "http://localhost/UAS_AchmadYani_WebProgram/processes/activate.php?token=$activation_token"
        ];
        
        if ($emailSent) {
            $response['message'] .= ' Silakan cek email Anda untuk aktivasi.';
        } else {
            $response['message'] .= ' Gunakan link berikut untuk aktivasi:';
        }
        
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
    }
}
?>