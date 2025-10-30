<?php
session_start();

// Load database configuration
$config_path = __DIR__ . '/../config/database.php';
if (!file_exists($config_path)) {
    die("Database config file not found: " . $config_path);
}
require_once $config_path;

// Load simple email sender (REPLACE the old email_sender)
$simple_email_path = __DIR__ . '/simple_email_sender.php';
if (file_exists($simple_email_path)) {
    require_once $simple_email_path;
}

// Simple Auth functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('../views/login.php');
    }
}

function requireLogout() {
    if (isLoggedIn()) {
        redirect('../views/dashboard.php');
    }
}

// Helper functions
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function sendActivationEmail($email, $name, $token) {
    $activationLink = "http://localhost/UAS_AchmadYani_WebProgram/processes/activate.php?token=" . $token;
    
    // Gunakan simple email sender
    if (class_exists('SimpleEmailSender')) {
        try {
            $emailSender = new SimpleEmailSender();
            $result = $emailSender->sendActivationEmail($email, $name, $activationLink);
            
            if ($result) {
                error_log("Email activation berhasil (simulasi) ke: $email");
                // Tetap simpan link untuk development
                saveActivationLinkForDevelopment($email, $activationLink);
                return true;
            }
        } catch (Exception $e) {
            error_log("Email sender error: " . $e->getMessage());
        }
    }
    
    // Fallback: selalu simpan link untuk development
    return saveActivationLinkForDevelopment($email, $activationLink);
}

function sendResetPasswordEmail($email, $name, $token) {
    $resetLink = "http://localhost/UAS_AchmadYani_WebProgram/views/reset_password.php?token=" . $token;
    
    // Gunakan simple email sender
    if (class_exists('SimpleEmailSender')) {
        try {
            $emailSender = new SimpleEmailSender();
            $result = $emailSender->sendResetPasswordEmail($email, $name, $resetLink);
            
            if ($result) {
                error_log("Email reset berhasil (simulasi) ke: $email");
                // Tetap simpan link untuk development
                saveResetLinkForDevelopment($email, $resetLink);
                return true;
            }
        } catch (Exception $e) {
            error_log("Email sender error: " . $e->getMessage());
        }
    }
    
    // Fallback: selalu simpan link untuk development
    return saveResetLinkForDevelopment($email, $resetLink);
}

function saveActivationLinkForDevelopment($email, $activationLink) {
    $log_message = "[" . date('Y-m-d H:i:s') . "] ACTIVATION LINK\n";
    $log_message .= "Email: $email\n";
    $log_message .= "Link: $activationLink\n";
    $log_message .= "----------------------------------------\n";
    
    file_put_contents(__DIR__ . '/../activation_links.txt', $log_message, FILE_APPEND);
    error_log("Activation Link saved for: $email");
    
    return true;
}

function saveResetLinkForDevelopment($email, $resetLink) {
    $log_message = "[" . date('Y-m-d H:i:s') . "] RESET PASSWORD LINK\n";
    $log_message .= "Email: $email\n";
    $log_message .= "Link: $resetLink\n";
    $log_message .= "----------------------------------------\n";
    
    file_put_contents(__DIR__ . '/../reset_links.txt', $log_message, FILE_APPEND);
    error_log("Reset Link saved for: $email");
    
    return true;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Simple database helper
function getDBConnection() {
    $database = new Database();
    return $database->getConnection();
}
?>