
<?php
include_once '../Model/teacher_db.php';
include_once '../Controller/teacher_db_control.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../Assets/teacher_dashboard.css" />
</head>
<body>

  <h1>Teacher Dashboard</h1>

  <div class="dashboard-section">
    <h2>Class Overview</h2>
    <form method="post" action="">
      <button type="submit" name="view-class-overview">View Overview</button>
    </form>
    <?php
      if (!empty($classOverviewOutput)) {
          echo $classOverviewOutput;
      }
    ?>
  </div>

  <div class="dashboard-section">
    <h2>See stragling student</h2>
    <form method="post" action="">
      <button type="submit" name="generate-alerts">Generate Alerts</button>
    </form>
    <?php
      if (!empty($alertsOutput)) {
          echo $alertsOutput;
      }
    ?>
  </div>

 <div class="dashboard-section">
  <label for="student analytics"> Students Analytics</label>
<button type="button" onclick="window.location.href='student_analytics.php'" >
  Go to Students Analytics Page
</button>
<label for="Question Randomization">Question Randomization</label>
<button type="button" onclick="window.location.href='randomization-settings.html'" >
  Go to Question Randomizing Page
</button>
<label for="import Question"> Import Question</label>
<button type="button" onclick="window.location.href='import.php'" >
  Go to Question Import Page
</button>
<label for="ganerate cirtificate"> Ganerate Cirtificate</label>
<button type="button" onclick="window.location.href='cirtificate_generation.php'" >
  Go to Cirtificate Ganeration Page
</button>

  </div>
  <button type="button" onclick="window.location.href='landingpage.php'">Back</button>

</form>

</body>
</html>



