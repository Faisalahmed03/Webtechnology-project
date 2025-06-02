<?php
include_once __DIR__ . '/includes/functions.php'; 
include_once __DIR__ . '/includes/db.php'; 
session_start();

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$email = '';

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
        
        $stmt = $conn->prepare("SELECT id, username, password_hash, role, is_verified FROM users WHERE email = ?");
        if ($stmt === false) {
            $errors[] = "Database error: Could not prepare statement. " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    if ($user['is_verified'] == 1) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role']; // Store user role
                        set_message("Login successful!", "success");
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $errors[] = "Please verify your email before logging in.";
                    }
                } else {
                    $errors[] = "Invalid email or password.";
                }
            } else {
                $errors[] = "Invalid email or password.";
            }
            $stmt->close();
        }
    }
}
$conn->close();
include_once 'includes/header.php';
?>

<h2>Login</h2>
<?php display_errors($errors); ?>
<?php display_message(); ?>

<form action="login.php" method="post">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <input type="submit" value="Login">
    </div>
</form>
<p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
<p><a href="forgot_password.php">Forgot your password?</a></p>

<?php include_once 'includes/footer.php'; ?>
