<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if email exists and account is active
    $query = "SELECT id, full_name FROM users WHERE email = :email AND status = 'active'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $reset_token = generateToken();
        $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Update user with reset token
        $query = "UPDATE users SET reset_token = :reset_token, token_expiry = :token_expiry WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':reset_token', $reset_token);
        $stmt->bindParam(':token_expiry', $token_expiry);
        $stmt->bindParam(':id', $user['id']);
        
        if ($stmt->execute()) {
            // Send reset email
            $emailSent = sendResetPasswordEmail($email, $user['full_name'], $reset_token);
            
            $response = [
                'success' => true, 
                'message' => 'Link reset password telah dikirim.',
                'reset_link' => "http://localhost/UAS_AchmadYani_WebProgram/views/reset_password.php?token=$reset_token"
            ];
            
            if (!$emailSent) {
                $response['message'] .= ' Gunakan link berikut:';
            }
            
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email tidak ditemukan atau akun belum aktif']);
    }
}
?>