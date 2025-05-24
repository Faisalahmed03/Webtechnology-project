<?php
include_once '../Model/teacher_dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="../Public/tracher_dashboard.css">
</head>
<body>

<form method="post" action="">
  <h1>Teacher Dashboard</h1>

  <div class="dashboard-section">
    <h2>Class Overview</h2>
    <button type="submit" name="view-class-overview">View Overview</button>
   <?php
      if (!empty($classOverviewOutput)) {
          echo $classOverviewOutput;
      }
    ?>
  </div>

  <div class="dashboard-section">
    <h2>See Struggling Students</h2>
    <button type="submit" name="generate-alerts">Generate Alerts</button>
   <?php
      if (!empty($alertsOutput)) {
          echo $alertsOutput;
      }
    ?>
  </div>

  <div class="dashboard-section">
<label for="Question Randomization">Question Randomization</label>
<button type="button" onclick="window.location.href='randomization-settings.html'" >
  Go to Question Randomizing Page
</button>

<label for="import Question"> Import Question</label>
<button type="button" onclick="window.location.href='import.html'" >
  Go to Question Import Page
</button>
<label for="ganerate cirtificate"> Ganerate Cirtificate</label>
<button type="button" onclick="window.location.href='cirtificate_generation.html'" >
  Go to Cirtificate Ganeration Page
</button>
<label for="student analytics"> Students Analytics</label>
<button type="button" onclick="window.location.href='student_analytics.html'" >
  Go to Students Analytics Page
</button>
  </div>
</form>

<div id="output">

</div>

</body>
</html>
