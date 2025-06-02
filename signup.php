<?php
include_once 'includes/functions.php';
include_once 'includes/db.php'; // For database connection
session_start();

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$username = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (!is_required($username)) {
        $errors[] = "Username is required.";
    }
    if (!is_required($email)) {
        $errors[] = "Email is required.";
    } elseif (!is_valid_email($email)) {
        $errors[] = "Invalid email format.";
    }
    if (!is_required($password)) {
        $errors[] = "Password is required.";
    } elseif (!is_strong_password($password)) {
        $errors[] = "Password must be at least 8 characters long and include at least one number, one uppercase letter, and one lowercase letter.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email or username already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        if ($stmt === false) {
            $errors[] = "Database error: Could not prepare statement. " . $conn->error;
        } else {
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $existing_user = $result->fetch_assoc();
                // This check is basic. You might want to specify if it's email or username.
                $errors[] = "Email or Username already exists."; 
            }
            $stmt->close();
        }
    }
    

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(32)); // Generate a verification token
        $role = 'user'; // Default role

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, verification_token, role) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            $errors[] = "Database error: Could not prepare insert statement. " . $conn->error;
        } else {
            $stmt->bind_param("sssss", $username, $email, $password_hash, $verification_token, $role);
            if ($stmt->execute()) {
                // Send verification email (pseudo-code)
                // $verify_link = "http://yourdomain.com/verify_email.php?token=" . $verification_token;
                // mail($email, "Verify Your Email", "Click here to verify: " . $verify_link);
                
                set_message("Registration successful! Please check your email to verify your account.", "success");
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again. " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$conn->close();
include_once 'includes/header.php';
?>

<h2>Sign Up</h2>
<?php display_errors($errors); ?>

<form action="signup.php" method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <input type="submit" value="Sign Up">
    </div>
</form>
<p>Already have an account? <a href="login.php">Login here</a>.</p>

<?php include_once 'includes/footer.php'; ?>
