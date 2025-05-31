<?php
include '../Model/admin_login_db.php';

$filterOutput = $updateOutput = $viewOutput = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach (['add', 'save', 'delete'] as $action) {
        if (!empty($_POST[$action])) {
            foreach ($_POST[$action] as $table => $rows) {
                if ($action === 'add') {
                    $data = array_filter($rows, fn($v) => trim($v) !== '');
                    if ($data) {
                        $columns = implode(", ", array_keys($data));
                        $values = implode("','", array_map([$conn, 'real_escape_string'], array_values($data)));
                        $conn->query("INSERT INTO `$table` ($columns) VALUES ('$values')");
                    }
                } else {
                    foreach ($rows as $id => $data) {
                        if ($action === 'save') {
                            $set = [];
                            foreach ($data as $col => $val) {
                                $set[] = "`$col`='" . $conn->real_escape_string($val) . "'";
                            }
                            $conn->query("UPDATE `$table` SET " . implode(", ", $set) . " WHERE id=$id");
                        } elseif ($action === 'delete') {
                            $conn->query("DELETE FROM `$table` WHERE id=$id");
                        }
                    }
                }
            }
        }
    }

  
    if (isset($_POST['filter-users'])) {
        $filterOutput = '<form method="post">
            <label>Select Grade:</label>
            <select name="selected-grade" required>
                <option value="A">A</option><option value="A+">A+</option>
                <option value="B">B</option><option value="B+">B+</option>
                <option value="C+">C+</option><option value="F">F</option>
            </select>
            <button type="submit" name="apply-grade-filter">Apply</button>
        </form>';
    }

    if (isset($_POST['apply-grade-filter'])) {
        $grade = $_POST['selected-grade'];
        $stmt = $conn->prepare("SELECT * FROM streport WHERE grade = ?");
        $stmt->bind_param("s", $grade);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $filterOutput .= "<h3>Results for grade '$grade'</h3><table border='1'><tr>";
            foreach ($result->fetch_fields() as $field) {
                $filterOutput .= "<th>{$field->name}</th>";
            }
            $filterOutput .= "</tr>";
            while ($row = $result->fetch_assoc()) {
                $filterOutput .= "<tr><td>" . implode("</td><td>", array_map('htmlspecialchars', $row)) . "</td></tr>";
            }
            $filterOutput .= "</table>";
        } else {
            $filterOutput .= "<p>No results for grade '$grade'.</p>";
        }
    }

   
    if (isset($_POST['update-info'])) {
        foreach (['teacher', 'student', 'streport', 'admininfo'] as $table) {
            $result = $conn->query("SELECT * FROM `$table`");
            $updateOutput .= "<h3>$table</h3>";
            if ($result->num_rows > 0) {
                $updateOutput .= "<form method='post'><table border='1'><tr>";
                $fields = $result->fetch_fields();
                foreach ($fields as $f) $updateOutput .= "<th>{$f->name}</th>";
                $updateOutput .= "<th>Action</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'] ?? null;
                    if (!$id) continue;
                    $updateOutput .= "<tr>";
                    foreach ($row as $k => $v) {
                        $updateOutput .= "<td><input type='text' name='save[$table][$id][$k]' value='" . htmlspecialchars($v) . "'></td>";
                    }
                    $updateOutput .= "<td><button type='submit'>Save</button>
                        <button type='submit' name='delete[$table][$id]'>Delete</button></td></tr>";
                }

        
                $updateOutput .= "<tr>";
                foreach ($fields as $f) {
                    $updateOutput .= "<td><input type='text' name='add[$table][{$f->name}]'></td>";
                }
                $updateOutput .= "<td><button type='submit'>Add</button></td></tr>";
                $updateOutput .= "</table></form><br>";
            }
        }
    }

 
    if (isset($_POST['view'])) {
        foreach (['teacher', 'student', 'streport', 'admininfo'] as $table) {
            $result = $conn->query("SELECT * FROM `$table`");
            $viewOutput .= "<h3>$table</h3>";
            if ($result->num_rows > 0) {
                $viewOutput .= "<table border='1'><tr>";
                foreach ($result->fetch_fields() as $f) {
                    $viewOutput .= "<th>{$f->name}</th>";
                }
                $viewOutput .= "</tr>";
                while ($row = $result->fetch_assoc()) {
                    $viewOutput .= "<tr><td>" . implode("</td><td>", array_map('htmlspecialchars', $row)) . "</td></tr>";
                }
                $viewOutput .= "</table><br>";
            }
        }
    }
}
?>
