<?php
include_once 'includes/functions.php';
include_once 'includes/db.php';
session_start();

$errors = [];
$token = isset($_GET['token']) ? sanitize_input($_GET['token']) : '';
$user_id = null;

if (empty($token)) {
    set_message("Invalid or missing reset token.", "error");
    header("Location: login.php");
    exit();
}


$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
if ($stmt === false) {
    $errors[] = "Database error: " . $conn->error;
} else {
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
    } else {
        set_message("Invalid or expired reset token.", "error");
        header("Location: login.php");
        exit();
    }
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $user_id) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!is_required($password)) {
        $errors[] = "New password is required.";
    } elseif (!is_strong_password($password)) {
        $errors[] = "Password must be at least 8 characters long and include at least one number, one uppercase letter, and one lowercase letter.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // Update password and clear reset token
        $update_stmt = $conn->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        if ($update_stmt === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $update_stmt->bind_param("si", $password_hash, $user_id);
            if ($update_stmt->execute()) {
                set_message("Your password has been reset successfully. You can now login.", "success");
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Failed to reset password. Please try again. " . $update_stmt->error;
            }
            $update_stmt->close();
        }
    }
}
$conn->close();
include_once 'includes/header.php';
?>

<h2>Reset Password</h2>
<?php display_errors($errors); ?>

<?php if ($user_id): // Only show form if token was valid initially ?>
<form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
    <div>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <input type="submit" value="Reset Password">
    </div>
</form>
<?php else: ?>
    <p class="error">This link is invalid or has expired. Please request a new password reset.</p>
    <p><a href="forgot_password.php">Request new link</a></p>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
