<?php
include_once __DIR__ . '/../includes/functions.php';
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../models/User.php';
session_start();

$errors = [];
$email = '';

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    if (!is_required($email)) {
        $errors[] = "Email is required.";
    } elseif (!is_valid_email($email)) {
        $errors[] = "Invalid email format.";
    }
    if (!is_required($password)) {
        $errors[] = "Password is required.";
    }
    if (empty($errors)) {
        $userModel = new User($conn);
        $user = $userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['is_verified'] != 1) {
                $errors[] = "Please verify your email before logging in.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
include __DIR__ . '/../views/login_view.php';
$conn->close();
