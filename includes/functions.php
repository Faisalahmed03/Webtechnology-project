<?php



function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}




function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function is_required($value) {
    return !empty(trim($value));
}


function is_strong_password($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match("/[0-9]/", $password)) return false;
    if (!preg_match("/[A-Z]/", $password)) return false;
    if (!preg_match("/[a-z]/", $password)) return false;

    return true;
}


function display_errors($errors_array) {
    if (!empty($errors_array)) {
        echo '<div class="error-messages">'; 
        echo '<ul>';
        foreach ($errors_array as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>'; 
    }
}




function is_logged_in() {
    return isset($_SESSION['user_id']);
}


function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}


function has_role($role) {
    return (isset($_SESSION['role']) && $_SESSION['role'] == $role);
}


function require_role($role) {
    require_login();
    if (!has_role($role)) {
        
        $_SESSION['message'] = "You are not authorized to access this page.";
        $_SESSION['msg_type'] = "error";
        header("Location: dashboard.php"); 
        exit();
    }
}


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
