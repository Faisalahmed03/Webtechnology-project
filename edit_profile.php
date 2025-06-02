<?php
include_once 'includes/functions.php';
include_once 'includes/db.php';
session_start();
require_login();

$user_id = $_SESSION['user_id'];
$errors = [];
$first_name = '';
$last_name = '';
$email = ''; 


$stmt_fetch = $conn->prepare("SELECT email, first_name, last_name FROM users WHERE id = ?");
if ($stmt_fetch) {
    $stmt_fetch->bind_param("i", $user_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    if ($user_data = $result->fetch_assoc()) {
        $email = $user_data['email'];
        $first_name = $user_data['first_name'];
        $last_name = $user_data['last_name'];
    }
    $stmt_fetch->close();
} else {
    $errors[] = "Error fetching user data: " . $conn->error;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_first_name = sanitize_input($_POST['first_name']);
    $new_last_name = sanitize_input($_POST['last_name']);
    
 }


    if (empty($errors)) {
        
        $stmt_update = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
        if ($stmt_update === false) {
            $errors[] = "Database error preparing update: " . $conn->error;
        } else {
            $stmt_update->bind_param("ssi", $new_first_name, $new_last_name, $user_id);
            if ($stmt_update->execute()) {
                set_message("Profile updated successfully!", "success");
            
                
                header("Location: profile.php");
                exit();
            } else {
                $errors[] = "Failed to update profile. " . $stmt_update->error;
            }
            $stmt_update->close();
        }
    }
    
    $first_name = $new_first_name;
    $last_name = $new_last_name;
    

$conn->close();
include_once 'includes/header.php';
?>

<h2>Edit Profile</h2>
<?php display_errors($errors); ?>
<?php display_message(); ?>

<form action="edit_profile.php" method="post">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
        <small>Email cannot be changed here. Contact support if needed.</small>
    </div>
    <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
    </div>
    <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
    </div>
    <div>
        <input type="submit" value="Save Changes">
    </div>
</form>
<p><a href="profile.php">Back to Profile</a></p>

<?php include_once 'includes/footer.php'; ?>
