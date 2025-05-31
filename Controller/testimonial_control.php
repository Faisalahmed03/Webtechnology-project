<?php
include '../Model/teacher_db.php';

$testimonial = [];

$sql = "SELECT name, message FROM contact ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonial[] = $row;
    }
   
}

$conn->close();
?>
