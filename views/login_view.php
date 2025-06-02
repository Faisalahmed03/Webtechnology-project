<?php
include_once __DIR__ . '/../includes/header.php';
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
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
