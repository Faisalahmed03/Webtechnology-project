<?php
include '../Controller/download_questions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bulk Import Questions</title>
  <link rel="stylesheet" href="../Assets/import.css">
</head>
<body class="body">
  <h1>Bulk Import Questions</h1>

  <div class="bulk-import-section">
    <h2>Add Question</h2>
    <button type="button" class="button" onclick="window.location.href='question_form.php'">
      Create New Questions
    </button>
    <h2>Download the Question Template</h2>
   <a href="../Assets/question_template.csv" download>
  <button type="button" class="button">Download Template</button>
</a>
</div>

    <button type="button" class="button" onclick="window.location.href='teacher_dashboard.php'">
    Back
  </button>
</body>
</html>
