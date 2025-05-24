<?php
session_start();
include_once '../Model/admin_login_db.php';

$host = "localhost";
$db = "online_quiz";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (check_admin_login($conn, $username, $password)) {
    $_SESSION['admin_username'] = $username;
    header("Location: admin_page.php");
    exit();
  } else {
    $message = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link rel="stylesheet" href="../Public/teacher-login.css" />
</head>
<body>

<div class="login-container">
  <h2>Admin Login</h2>
  <?php if (!empty($message)) : ?>
    <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  
  <form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</div>

</body>
</html>
