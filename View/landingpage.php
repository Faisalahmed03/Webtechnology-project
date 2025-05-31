
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Landing page</title>
  <link rel="stylesheet" href="../Assets/landingpage.css" />
</head>
</head>
<body>

  <nav>
    <a href="#home">Home</a>
    <a href="#features">Features</a>
    <a href="#testimonials">Testimonials</a>
    <a href="#signup">Sign Up</a>
    <a href="contact.php">Contact us</a>
  </nav>

  <header id="home">
    <h1>Welcome to Online Quiz</h1>
  </header>

  <section id="features">
    <h2>Key Features</h2>
    <ul>
      <li>Bulk Import Questions with Preview</li>
      <li>Randomized Question Generation with Lockdown Option</li>
      <li>Admin & Teacher Dashboards with Real-Time Analytics</li>
      <li>Student Skill Matrix and Certificate Generation</li>
    </ul>
  </section>

  <section id="testimonials">
     <h2>What Our Users Say</h2>
  <?php
include '../Model/teacher_db.php';
    $sql = "SELECT name, message FROM contact ORDER BY created_at DESC LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<div class='testimonial'><strong>" . htmlspecialchars($row['name']) . ":</strong> \"" . htmlspecialchars($row['message']) . "\"</div>";
      }
    } else {
      echo "<p>No testimonials yet.</p>";
    }

    $conn->close();
  ?>
  </section>

  <section id="signup">
    <h2>Get Started</h2>
    <p>Sign up to access our intelligent quiz system tailored to boost learning outcomes.</p>
    <div class="cta-container">
<button class="cta-button" onclick="toggleCreateOptions()">Create Account</button>
      <button class="cta-button" onclick="toggleLoginOptions()">Log In</button>
    </div>

    <div id="login-options"  style="display: none;">
      
      <ul>
        <label for="ul"><b>Login as</b></label>
       <li onclick="window.location.href='teacher_login.php'">Teacher</li>
        <li onclick="window.location.href='student_login.php'">Student</li>
        <li onclick="window.location.href='admin_login.php'">Admin</li>
      </ul>
    </div>
    <div id="create-options" style="display: none;">
  <ul>
    <label for="ul"><b>Create Account as</b></label>
    <li onclick="window.location.href='student_signup.php'">Student</li>
    <li onclick="window.location.href='teacher_signup.php'">Teacher</li>
  </ul>
</div>
  </section>

 <script src="../Assets/landingpage.js"></script>

</body>
</html>
