<<<<<<< HEAD
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate name (only letters and spaces allowed)
    if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
        echo "Name is required and should only contain letters and spaces.";
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    }

    // Validate password and confirm password
    if (empty($password)) {
        echo "Password is required.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    }

    // If all fields are valid, proceed with the signup (e.g., store in the database)
    if (!empty($name) && !empty($email) && !empty($password) && $password === $confirm_password) {
        // Hash the password before storing it in the database (example)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Store user information in database (add your database code here)
        // For example: save to database using PDO or MySQLi

        echo "Signup successful!";
    }
}
?>
=======
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate name (only letters and spaces allowed)
    if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
        echo "Name is required and should only contain letters and spaces.";
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    }

    // Validate password and confirm password
    if (empty($password)) {
        echo "Password is required.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    }

    // If all fields are valid, proceed with the signup (e.g., store in the database)
    if (!empty($name) && !empty($email) && !empty($password) && $password === $confirm_password) {
        // Hash the password before storing it in the database (example)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Store user information in database (add your database code here)
        // For example: save to database using PDO or MySQLi

        echo "Signup successful!";
    }
}
?>
>>>>>>> d232144 (project)
