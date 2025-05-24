<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_quiz";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filterOutput = "";
$updateOutput = "";
$viewOutput = "";

// Handle Save, Delete, and Add
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach (['save', 'delete', 'add'] as $action) {
        if (!empty($_POST[$action])) {
            foreach ($_POST[$action] as $table => $rows) {
                if ($action === 'add') {
    // Filter out empty values
    $nonEmptyRows = array_filter($rows, function ($value) {
        return trim($value) !== '';
    });

    // Only insert if there's at least one non-empty value
    if (!empty($nonEmptyRows)) {
        $columns = implode(", ", array_keys($nonEmptyRows));
        $values = implode("','", array_map([$conn, 'real_escape_string'], array_values($nonEmptyRows)));
        $query = "INSERT INTO `$table` ($columns) VALUES ('$values')";
        $conn->query($query);
    }
}
else {
                    foreach ($rows as $id => $data) {
                        if ($action === 'save') {
                            $updates = [];
                            foreach ($data as $col => $val) {
                                $updates[] = "`$col`='" . $conn->real_escape_string($val) . "'";
                            }
                            $query = "UPDATE `$table` SET " . implode(", ", $updates) . " WHERE id=$id";
                            $conn->query($query);
                        } elseif ($action === 'delete') {
                            $query = "DELETE FROM `$table` WHERE id=$id";
                            $conn->query($query);
                        }
                    }
                }
            }
        }
    }
}

// Filter by Grade
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filter-users'])) {
        $filterOutput = '
            <form method="post">
                <label for="grade">Select Grade to Filter:</label>
                <select name="selected-grade" id="grade" required>
                    <option value="A">A</option>
                    <option value="A+">A+</option>
                    <option value="B">B</option>
                    <option value="B+">B+</option>
                    <option value="C+">C+</option>
                    <option value="F">F</option>
                </select>
                <button type="submit" name="apply-grade-filter">Apply Filter</button>
            </form>
        ';
    }

    if (isset($_POST['apply-grade-filter'])) {
        $grade = $_POST['selected-grade'];
        $stmt = $conn->prepare("SELECT * FROM streport WHERE grade = ?");
        $stmt->bind_param("s", $grade);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $filterOutput .= "<h3>Filtered Results for Grade '" . htmlspecialchars($grade) . "'</h3><table border='1'><tr>";
            foreach ($result->fetch_fields() as $field) {
                $filterOutput .= "<th>" . htmlspecialchars($field->name) . "</th>";
            }
            $filterOutput .= "</tr>";
            while ($row = $result->fetch_assoc()) {
                $filterOutput .= "<tr>";
                foreach ($row as $value) {
                    $filterOutput .= "<td>" . htmlspecialchars($value) . "</td>";
                }
                $filterOutput .= "</tr>";
            }
            $filterOutput .= "</table>";
        } else {
            $filterOutput .= "<p>No users found with grade '" . htmlspecialchars($grade) . "'.</p>";
        }
        $stmt->close();
    }

    // Update Information (Editable)
    if (isset($_POST['update-info'])) {
        $tables = ['teacher', 'student', 'streport', 'admininfo'];

        foreach ($tables as $table) {
            $result = $conn->query("SELECT * FROM `$table`");
            $updateOutput .= "<h3>Table: $table</h3>";

            if ($result && $result->num_rows > 0) {
                $updateOutput .= "<form method='post'><table border='1'><tr>";
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    $updateOutput .= "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                $updateOutput .= "<th>Action</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    if (!isset($row['id'])) continue;
                    $id = $row['id'];
                    $updateOutput .= "<tr>";
                    foreach ($row as $key => $value) {
                        $updateOutput .= "<td><input type='text' name='save[$table][$id][$key]' value='" . htmlspecialchars($value) . "'></td>";
                    }
                    $updateOutput .= "<td>
                        <button type='submit'>Save</button>
                        <button type='submit' name='delete[$table][$id]'>Delete</button>
                    </td></tr>";
                }

                $updateOutput .= "<tr>";
                foreach ($fields as $field) {
                    $updateOutput .= "<td><input type='text' name='add[$table][{$field->name}]'></td>";
                }
                $updateOutput .= "<td><button type='submit'>Add</button></td></tr>";
                $updateOutput .= "</table></form><br>";
            } else {
                $updateOutput .= "<p>No data in table '$table'</p>";
            }
        }
    }

    // View All Information (Read-only)
    if (isset($_POST['view'])) {
        $tables = ['teacher', 'student', 'streport', 'admininfo'];

        foreach ($tables as $table) {
            $result = $conn->query("SELECT * FROM `$table`");
            $viewOutput .= "<h3>Table: $table</h3>";

            if ($result && $result->num_rows > 0) {
                $viewOutput .= "<table border='1'><tr>";
                foreach ($result->fetch_fields() as $field) {
                    $viewOutput .= "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                $viewOutput .= "</tr>";

                while ($row = $result->fetch_assoc()) {
                    $viewOutput .= "<tr>";
                    foreach ($row as $value) {
                        $viewOutput .= "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    $viewOutput .= "</tr>";
                }

                $viewOutput .= "</table><br>";
            } else {
                $viewOutput .= "<p>No data in table '$table'</p>";
            }
        }
    }
}
?>