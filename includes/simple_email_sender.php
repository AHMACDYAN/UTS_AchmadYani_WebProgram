<?php
class SimpleEmailSender {
    
    public function sendActivationEmail($toEmail, $toName, $activationLink) {
        $subject = 'Aktivasi Akun - Admin Gudang';
        $message = $this->getActivationEmailText($toName, $activationLink);
        
        return $this->sendEmail($toEmail, $subject, $message);
    }
    
    public function sendResetPasswordEmail($toEmail, $toName, $resetLink) {
        $subject = 'Reset Password - Admin Gudang';
        $message = $this->getResetPasswordEmailText($toName, $resetLink);
        
        return $this->sendEmail($toEmail, $subject, $message);
    }
    
    private function sendEmail($toEmail, $subject, $message) {
        $headers = "From: Admin Gudang <noreply@admin-gudang.com>\r\n";
        $headers .= "Reply-To: noreply@admin-gudang.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Simpan ke file log (karena email mungkin tidak terkirim di localhost)
        $this->logEmail($toEmail, $subject, $message);
        
        // Coba kirim email (akan work di hosting real)
        if (mail($toEmail, $subject, $message, $headers)) {
            error_log("Email berhasil dikirim ke: $toEmail");
            return true;
        } else {
            error_log("Email GAGAL dikirim ke: $toEmail");
            return false;
        }
    }
    
    private function logEmail($toEmail, $subject, $message) {
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'to' => $toEmail,
            'subject' => $subject,
            'message' => $message
        ];
        
        $log_message = "=== EMAIL LOG ===\n";
        $log_message .= "Time: " . $log_data['timestamp'] . "\n";
        $log_message .= "To: " . $log_data['to'] . "\n";
        $log_message .= "Subject: " . $log_data['subject'] . "\n";
        $log_message .= "Message:\n" . $log_data['message'] . "\n";
        $log_message .= "================\n\n";
        
        file_put_contents(__DIR__ . '/../email_log.txt', $log_message, FILE_APPEND);
    }
    
    private function getActivationEmailText($name, $activationLink) {
        return "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;'>
                <div style='background: #007bff; color: white; padding: 20px; text-align: center;'>
                    <h1>Admin Gudang System</h1>
                </div>
                <div style='padding: 20px;'>
                    <h2>Halo, $name!</h2>
                    <p>Terima kasih telah mendaftar di sistem Admin Gudang.</p>
                    <p>Silakan klik link berikut untuk mengaktifkan akun Anda:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='$activationLink' style='background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Aktivasi Akun
                        </a>
                    </p>
                    <p>Atau copy link berikut ke browser Anda:</p>
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                        <code style='word-break: break-all;'>$activationLink</code>
                    </div>
                    <p>Jika Anda tidak merasa mendaftar, silakan abaikan email ini.</p>
                </div>
                <div style='background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px;'>
                    <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    private function getResetPasswordEmailText($name, $resetLink) {
        return "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;'>
                <div style='background: #dc3545; color: white; padding: 20px; text-align: center;'>
                    <h1>Reset Password</h1>
                </div>
                <div style='padding: 20px;'>
                    <h2>Halo, $name!</h2>
                    <p>Kami menerima permintaan reset password untuk akun Anda.</p>
                    <p>Silakan klik link berikut untuk membuat password baru:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='$resetLink' style='background: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Reset Password
                        </a>
                    </p>
                    <p>Atau copy link berikut ke browser Anda:</p>
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                        <code style='word-break: break-all;'>$resetLink</code>
                    </div>
                    <p><strong>Perhatian:</strong> Link ini berlaku selama 1 jam.</p>
                    <p>Jika Anda tidak meminta reset password, silakan abaikan email ini.</p>
                </div>
                <div style='background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px;'>
                    <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
?>