<?php
include_once 'includes/functions.php';
include_once 'includes/db.php';
session_start();

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$email = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);

    if (!is_required($email)) {
        $errors[] = "Email is required.";
    } elseif (!is_valid_email($email)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND is_verified = 1");
        if ($stmt === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $reset_token = bin2hex(random_bytes(32));
                $token_expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

                $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
                if($update_stmt === false) {
                    $errors[] = "Database error: " . $conn->error;
                } else {
                    $update_stmt->bind_param("sss", $reset_token, $token_expiry, $email);
                    if ($update_stmt->execute()) {
                        // Send password reset email (pseudo-code)
                        // $reset_link = "http://yourdomain.com/reset_password.php?token=" . $reset_token;
                        // mail($email, "Password Reset Request", "Click here to reset your password: " . $reset_link);
                        
                        $message = "If an account with that email exists, a password reset link has been sent.";
                        // To prevent user enumeration, show the same message whether email exists or not.
                    } else {
                        $errors[] = "Failed to update reset token. Please try again. " . $update_stmt->error;
                    }
                    $update_stmt->close();
                }
            } else {
                 // To prevent user enumeration, show the same message whether email exists or not.
                $message = "If an account with that email exists, a password reset link has been sent.";
            }
            $stmt->close();
        }
    }
}
$conn->close();
include_once 'includes/header.php';
?>

<h2>Forgot Password</h2>

<?php if (!empty($message)): ?>
    <p class="success"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<?php display_errors($errors); ?>

<form action="forgot_password.php" method="post">
    <div>
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div>
        <input type="submit" value="Send Reset Link">
    </div>
</form>
<p><a href="login.php">Back to Login</a></p>

<?php include_once 'includes/footer.php'; ?>
