<?php
include '../Model/students_analytics_db.php';
include '../Controller/student_analytic_control.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Analytics</title>
  <link rel="stylesheet" href="../Assets/student_analytics.css" />
</head>
<body>
  <form method="POST">
    <h1>Student Analytics</h1>

    <div class="analytics-section">
      <h2>Skill Matrix</h2>
      <label for="student-id">Student ID</label>
      <input type="text" id="student-id" name="student-id" placeholder="e.g., 3">
      <button type="submit" name="submit-skill-matrix">View Skill Matrix</button>
      <?php if (!empty($output)): ?>
        <div class="analytics-box"><?= $output ?></div>
      <?php endif; ?>
    </div>


    <div class="analytics-section">
      <h2>Weakness Report</h2>
      <label for="report-topic">Select Topic</label>
      <select id="report-topic" name="report-topic">
        <option value="physics">Physics</option>
        <option value="math">Math</option>
        <option value="english">English</option>
      </select>
      <button type="submit" name="submit-weakness-report">Generate Report</button>
      <?php if (!empty($report)): ?>
        <div class="analytics-box"><?= $report ?></div>
      <?php endif; ?>
    </div>
  </form>

  <button type="button" onclick="window.location.href='teacher_dashboard.php'">Back</button>
</body>
</html>
