<?php
include '../Model/teacher_db.php';
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM teacher WHERE username=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    header("Location: teacher_dashboard.php");
    exit();
  } else {
    $message = "Invalid username or password.";
  }
}
?>