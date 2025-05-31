<?php
include '../Model/teacher_db.php';

$filePath = '../Assets/question_template.csv'; 
$output = fopen($filePath, 'w');
if (!$output) {
    die("Failed to open file for writing.");
}
fputcsv($output, ['Test ID', 'Question Text', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer']);

$sql = "SELECT test_id, question_text, options_json, correct_answer FROM questions";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $options = json_decode($row['options_json'], true);
        fputcsv($output, [
            $row['test_id'],
            $row['question_text'],
            $options['A'] ?? '',
            $options['B'] ?? '',
            $options['C'] ?? '',
            $options['D'] ?? '',
            $row['correct_answer']
        ]);
    }
} else {
    die("Query failed: " . $conn->error);
}
fclose($output);
$conn->close();

?>
