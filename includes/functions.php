<?php
// Common functions will go here

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// --- Form Validation Functions ---

// Validate Email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate Required Field
function is_required($value) {
    return !empty(trim($value));
}

// Validate Password Strength (example: at least 8 chars, 1 num, 1 upper, 1 lower)
function is_strong_password($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match("/[0-9]/", $password)) return false;
    if (!preg_match("/[A-Z]/", $password)) return false;
    if (!preg_match("/[a-z]/", $password)) return false;
    // if (!preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) return false; // Optional special char
    return true;
}

// Display validation errors
function display_errors($errors_array) {
    if (!empty($errors_array)) {
        echo '<div class="error-messages">'; // Removed unnecessary backslash
        echo '<ul>';
        foreach ($errors_array as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>'; // Removed unnecessary backslash
    }
}

// --- User Authentication Functions ---

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Function to check user role
function has_role($role) {
    return (isset($_SESSION['role']) && $_SESSION['role'] == $role);
}

// Function to require a specific role
function require_role($role) {
    require_login();
    if (!has_role($role)) {
        // Redirect to a "not authorized" page or dashboard
        $_SESSION['message'] = "You are not authorized to access this page.";
        $_SESSION['msg_type'] = "error";
        header("Location: dashboard.php"); 
        exit();
    }
}

// --- Session Messages ---
function set_message($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['msg_type'] = $type;
}

function display_message() {
    if (isset($_SESSION['message'])) {
        echo '<div class="message ' . $_SESSION['msg_type'] . '">' . htmlspecialchars($_SESSION['message']) . '</div>'; // Removed unnecessary backslash
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
    }
}

?>
