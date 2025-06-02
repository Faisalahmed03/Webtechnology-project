<?php
include_once 'includes/functions.php';
include_once 'includes/db.php';
session_start();
require_login();

$user_id = $_SESSION['user_id'];
$errors = [];
$target_dir = "uploads/avatars/"; 
$default_avatar_path = "assets/default_avatar.png"; 


if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check !== false) {
    
        $uploadOk = 1;
    } else {
        $errors[] = "File is not an image.";
        $uploadOk = 0;
    }


    if ($_FILES["avatar"]["size"] > 5000000) {
        $errors[] = "Sorry, your file is too large (max 5MB).";
        $uploadOk = 0;
    }

    
    $allowed_types = ["jpg", "png", "jpeg", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 0) {
        $errors[] = "Sorry, your file was not uploaded.";
    
    } else {
        
        $new_filename = $target_dir . $user_id . '_' . time() . '.' . $imageFileType;
        
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $new_filename)) {
            
            $stmt = $conn->prepare("UPDATE users SET profile_picture_path = ? WHERE id = ?");
            if ($stmt === false) {
                $errors[] = "Database error: " . $conn->error;
            } else {
                $stmt->bind_param("si", $new_filename, $user_id);
                if ($stmt->execute()) {
                    
                    
                    set_message("Profile picture updated successfully!", "success");
                    header("Location: profile.php");
                    exit();
                } 
                else {
                    $errors[] = "Failed to update profile picture in database. " . $stmt->error;
                    
                    if (file_exists($new_filename)) unlink($new_filename);
                }
                $stmt->close();
            }
        } else {
            $errors[] = "Sorry, there was an error uploading your file.";
        }
    }
}
$conn->close();
include_once 'includes/header.php';
?>

<h2>Change Profile Picture</h2>
<?php display_errors($errors); ?>
<?php display_message(); ?>

<form action="change_avatar.php" method="post" enctype="multipart/form-data">
    <div>
        <label for="avatar">Select image to upload:</label>
        <input type="file" name="avatar" id="avatar" required>
    </div>
    <div>
        <input type="submit" value="Upload Image" name="submit">
    </div>
</form>
<p><a href="profile.php">Back to Profile</a></p>

<?php include_once 'includes/footer.php'; ?>
