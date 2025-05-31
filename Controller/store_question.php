<?php
include '../Model/teacher_db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $test_id = $_POST['test_id'];
    $question_text = $_POST['question_text'];
    $correct_answer = strtoupper(trim($_POST['correct_answer']));

    $options = [
        "A" => $_POST['option_a'],
        "B" => $_POST['option_b'],
        "C" => $_POST['option_c'],
        "D" => $_POST['option_d']
    ];

    $options_json = json_encode($options, JSON_UNESCAPED_UNICODE);
    $sql = "INSERT INTO questions (test_id, question_text, options_json, correct_answer)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $test_id, $question_text, $options_json, $correct_answer);

    if ($stmt->execute()) {
echo "<script>alert('Question saved successfully!'); window.location.href = '../View/question_form.php';</script>";
    } else {
        echo "<script>alert(' Error: " . $stmt->error . "'); window.location.href = '../Controller/store_question.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
