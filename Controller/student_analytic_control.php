<?php
include_once '../Model/students_analytics_db.php'; 

$report = "";
$output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["submit-skill-matrix"])) {
        $studentId = trim($_POST["student-id"]);
        $stmt = $conn->prepare("SELECT * FROM streport WHERE student_id = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $output .= "<h2>Skill Matrix for Student ID: {$row['student_id']} - {$row['student_name']}</h2>";
            $output .= "<table border='1' cellpadding='10'>";
            $output .= "<tr><th>Subject</th><th>Marks</th></tr>";
            $output .= "<tr><td>Physics</td><td>{$row['Physics']}</td></tr>";
            $output .= "<tr><td>Math</td><td>{$row['Math']}</td></tr>";
            $output .= "<tr><td>English</td><td>{$row['English']}</td></tr>";
            $output .= "<tr><td><strong>Total</strong></td><td><strong>{$row['total']}</strong></td></tr>";
            $output .= "<tr><td><strong>Grade</strong></td><td><strong>{$row['grade']}</strong></td></tr>";
            $output .= "</table>";
        } else {
            $output = "<p>No student found with ID: $studentId</p>";
        }
        $stmt->close();
    }


    if (isset($_POST["submit-weakness-report"])) {
        $topic = $_POST["report-topic"];

        $subjectMap = [
            "physics" => "Physics",
            "math" => "Math",
            "english" => "English",
        ];

        $subject = $subjectMap[strtolower($topic)] ?? "Math"; 

        $sql = "SELECT student_name, $subject FROM streport ORDER BY $subject ASC LIMIT 5";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $report .= "<h2>Weakness Report for $subject</h2>";
            $report .= "<table border='1' cellpadding='10'>";
            $report .= "<tr><th>Student Name</th><th>$subject Score</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $report .= "<tr><td>{$row['student_name']}</td><td>{$row[$subject]}</td></tr>";
            }

            $report .= "</table>";
        } else {
            $report = "<p>No data found for Weakness Report in $subject.</p>";
        }
    }
}
?>
