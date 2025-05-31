<?php
include '../Controller/teacher_login_control.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Analytics</title>
  <link rel="stylesheet" href="../Assets/teacher-login.css" />
</head>
<body>

<div class="login-container">
  <h2>Teacher Login</h2>
  <form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button> <br><br>
    <?php if ($message): ?>
      <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
      <button type="button" onclick="window.location.href='landingpage.php'">Back</button>

  </form>
</div>

</body>
</html>
