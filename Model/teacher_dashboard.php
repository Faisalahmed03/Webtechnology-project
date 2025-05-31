<?php
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
?>
