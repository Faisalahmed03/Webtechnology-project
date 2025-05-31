<?php
session_start();
include '../Model/admin_login_db.php'; 

function check_admin_login($conn, $username, $password) {
    $sql = "SELECT * FROM admininfo WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) { 
            return true;
        }
    }

    return false;
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