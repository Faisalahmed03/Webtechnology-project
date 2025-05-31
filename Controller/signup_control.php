<?php

// Process the form if submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Basic validation
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // Check if the username already exists
    $checkStmt = $conn->prepare("SELECT id FROM student WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Username already exists.'); window.history.back();</script>";
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    // Store the user (password is stored as is — consider hashing in future)
    $stmt = $conn->prepare("INSERT INTO student (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Sign up successful!'); window.location.href='student_login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
