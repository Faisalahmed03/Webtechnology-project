<?php
include '../Model/teacher_db.php';
include '../Controller/signup_control.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Sign Up</title>
  <link rel="stylesheet" href="../Assets/student_signup.css" />
</head>
<body>
  <div class="login-container">
    <h2>Student Sign Up</h2>

    <form id="signup-form" action="student_signup.php" method="POST">
      <input type="text" id="username" name="username" placeholder="Username" />
      <div class="error-message" id="nameerror"></div> 

      <input type="password" id="password" name="password" placeholder="Password" />
      <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" />
      <div class="error-message" id="passworderror"></div>
      <button type="submit">Sign Up</button><br><br>
      <button type="button" onclick="window.location.href='landingpage.php'">Back</button>
    </form>
  </div>

  <script src="../Assets/student_signup.js"></script>
</body>
</html>
