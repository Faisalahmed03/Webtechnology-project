<?php
include '../Controller/store_question.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Question</title>
  <link rel="stylesheet" href="../Assets/add_question.css" />
</head>
<body>
<body>
  <div class="form-container">
    <h2>Add New Question</h2>

    <form action=" " method="POST">
      <label>Test ID:</label>
      <input type="number" name="test_id" required>

      <label>Question:</label>
      <textarea name="question_text" required></textarea>

      <label>Option A:</label>
      <input type="text" name="option_a" required>

      <label>Option B:</label>
      <input type="text" name="option_b" required>

      <label>Option C:</label>
      <input type="text" name="option_c" required>

      <label>Option D:</label>
      <input type="text" name="option_d" required>

      <label>Correct Answer (A/B/C/D):</label>
      <input type="text" name="correct_answer" pattern="[ABCD]" required>

      <button type="submit">Save Question</button>
       <button type="button" onclick="window.location.href='import.php'">Back</button>

    </form>
  </div>
</body>
</html>

