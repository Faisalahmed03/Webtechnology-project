<?php
include_once 'includes/functions.php';
include_once 'includes/db.php';
session_start();
require_login();

$user_id = $_SESSION['user_id'];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if (!is_required($current_password)) {
        $errors[] = "Current password is required.";
    }
    if (!is_required($new_password)) {
        $errors[] = "New password is required.";
    } elseif (!is_strong_password($new_password)) {
        $errors[] = "New password must be at least 8 characters long and include at least one number, one uppercase letter, and one lowercase letter.";
    }
    if ($new_password !== $confirm_new_password) {
        $errors[] = "New passwords do not match.";
    }

    if (empty($errors)) {
        // Fetch current password hash
        $stmt_fetch = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        if ($stmt_fetch === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt_fetch->bind_param("i", $user_id);
            $stmt_fetch->execute();
            $result = $stmt_fetch->get_result();
            if ($user_data = $result->fetch_assoc()) {
                if (password_verify($current_password, $user_data['password_hash'])) {
                    // Current password is correct, proceed to update
                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt_update = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                    if ($stmt_update === false) {
                        $errors[] = "Database error preparing update: " . $conn->error;
                    } else {
                        $stmt_update->bind_param("si", $new_password_hash, $user_id);
                        if ($stmt_update->execute()) {
                            set_message("Password updated successfully!", "success");
                            // Optionally, log out other sessions or notify user
                            header("Location: profile.php");
                            exit();
                        } else {
                            $errors[] = "Failed to update password. " . $stmt_update->error;
                        }
                        $stmt_update->close();
                    }
                } else {
                    $errors[] = "Incorrect current password.";
                }
            } else {
                $errors[] = "User not found."; // Should not happen
            }
            $stmt_fetch->close();
        }
    }
}
$conn->close();
include_once 'includes/header.php';
?>

<h2>Update Password</h2>
<?php display_errors($errors); ?>
<?php display_message(); ?>

<form action="update_password.php" method="post">
    <div>
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>
    <div>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
    </div>
    <div>
        <label for="confirm_new_password">Confirm New Password:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required>
    </div>
    <div>
        <input type="submit" value="Update Password">
    </div>
</form>
<p><a href="profile.php">Back to Profile</a></p>

<?php include_once 'includes/footer.php'; ?>
