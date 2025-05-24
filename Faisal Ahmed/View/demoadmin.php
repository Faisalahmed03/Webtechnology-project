<?php
// teacher_dashboard.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_quiz";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize outputs
$classOverviewOutput = "";
$alertsOutput = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // View Class Overview
    if (isset($_POST['view-class-overview'])) {
        $sql = "SELECT * FROM streport";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $classOverviewOutput .= "<h2>Class Overview</h2>";
            $classOverviewOutput .= "<table border='1' cellpadding='10'>";
            $classOverviewOutput .= "<tr>";

            $fields = $result->fetch_fields();
            foreach ($fields as $field) {
                $classOverviewOutput .= "<th>" . htmlspecialchars($field->name) . "</th>";
            }
            $classOverviewOutput .= "</tr>";

            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                $classOverviewOutput .= "<tr>";
                foreach ($row as $data) {
                    $classOverviewOutput .= "<td>" . htmlspecialchars($data) . "</td>";
                }
                $classOverviewOutput .= "</tr>";
            }
            $classOverviewOutput .= "</table>";
        } else {
            $classOverviewOutput = "<p>No records found in the streport table.</p>";
        }
    }

    // Generate Alerts
    if (isset($_POST['generate-alerts'])) {
        $sql = "SELECT student_id, student_name, grade FROM streport WHERE grade = 'F'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $alertsOutput .= "<h2>Students with Grade 'F'</h2>";
            $alertsOutput .= "<table border='1' cellpadding='10'>";
            $alertsOutput .= "<tr><th>Student ID</th><th>Student Name</th><th>Grade</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $alertsOutput .= "<tr>";
                $alertsOutput .= "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                $alertsOutput .= "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                $alertsOutput .= "<td>" . htmlspecialchars($row['grade']) . "</td>";
                $alertsOutput .= "</tr>";
            }

            $alertsOutput .= "</table>";
        } else {
            $alertsOutput .= "<p>No students with grade 'F' found.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Dashboard</title>
  <style>
    body {
      font-family: sans-serif;
    }

    .dashboard-section {
      margin-bottom: 30px;
      border: 1px solid #000;
      padding: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input, select, button {
      padding: 5px;
      margin-bottom: 10px;
      width: 100%;
      box-sizing: border-box;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
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
    <h2>Question-Level Difficulty Analysis</h2>
    <form method="post" action="">
      <label for="test-id">Enter Test ID</label>
      <input type="text" id="test-id" name="test-id" placeholder="e.g., TEST123">
      <button type="submit" name="analyze-difficulty">Analyze Difficulty</button>
    </form>
    <?php
      // Future feature: Add difficulty analysis logic here
    ?>
  </div>

</body>
</html>
