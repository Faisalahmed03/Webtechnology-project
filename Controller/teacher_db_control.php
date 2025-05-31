<?php

$classOverviewOutput = "";
$alertsOutput = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
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