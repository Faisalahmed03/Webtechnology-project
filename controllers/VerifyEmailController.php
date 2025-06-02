<?php
class VerifyEmailController {
    public function index() {
        include_once '../includes/functions.php';
        include_once '../includes/db.php';
        include_once '../app/models/User.php';
        session_start();

        $message = '';
        $msg_type = 'error';

        $userModel = new User($conn);
        if (isset($_GET['token'])) {
            $token = sanitize_input($_GET['token']);
            $user = $userModel->findByVerificationToken($token);
            if ($user) {
                if ($user['is_verified'] == 1) {
                    $message = "This email has already been verified. You can login.";
                    $msg_type = 'success';
                } else {
                    $verifyResult = $userModel->verifyUser($user['id']);
                    if ($verifyResult === true) {
                        $message = "Email verified successfully! You can now login.";
                        $msg_type = 'success';
                    } else {
                        $message = "Failed to verify email. Please try again. " . $verifyResult;
                    }
                }
            } else {
                $message = "Invalid or expired verification token.";
            }
        } else {
            $message = "No verification token provided.";
        }
        $conn->close();
        include '../app/views/verify_email/index.php';
    }
}
