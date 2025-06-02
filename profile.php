<?php
include_once 'includes/functions.php';
include_once 'includes/db.php';
session_start();
require_login(); // Ensure user is logged in

$user_id = $_SESSION['user_id'];
$user = null;
$errors = [];

// Fetch user data
$stmt = $conn->prepare("SELECT username, email, first_name, last_name, profile_picture_path FROM users WHERE id = ?");
if ($stmt === false) {
    $errors[] = "Database error: " . $conn->error;
} else {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        $errors[] = "User not found."; // Should not happen if session is valid
        // Potentially log out user or handle error
    }
    $stmt->close();
}
$conn->close();

include_once 'includes/header.php';
?>

<h2>My Profile</h2>
<?php display_errors($errors); ?>
<?php display_message(); ?>

<?php if ($user): ?>
    <div>
        <?php if (!empty($user['profile_picture_path']) && file_exists($user['profile_picture_path'])): ?>
            <img src="<?php echo htmlspecialchars($user['profile_picture_path']); ?>" alt="Profile Picture" style="width:150px; height:150px; border-radius:50%;">
        <?php else: ?>
            <p>No profile picture uploaded.</p>
        <?php endif; ?>
        <p><a href="change_avatar.php">Change Profile Picture</a></p>
    </div>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name'] ?? 'Not set'); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name'] ?? 'Not set'); ?></p>
    
    <p><a href="edit_profile.php">Edit Profile Details</a></p>
    <p><a href="update_password.php">Change Password</a></p>
<?php else: ?>
    <p>Could not load profile information.</p>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
