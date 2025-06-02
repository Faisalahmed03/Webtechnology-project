<?php
session_start();
// This will be the main entry point.
// If user is logged in, redirect to dashboard.php
// Else, redirect to login.php

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
