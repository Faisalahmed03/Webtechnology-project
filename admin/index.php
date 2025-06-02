<?php
include_once __DIR__ . '/../includes/functions.php';
session_start();
require_login();
require_role('admin'); 

include_once __DIR__ . '/../includes/header.php'; 
?>

<h2>Admin Dashboard</h2>
<?php display_message(); ?>

<p>Welcome to the Admin Panel, <?php echo htmlspecialchars($_SESSION['username']); ?>.</p>

<div class="admin-actions">
    <ul>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="permissions.php">Manage Roles & Permissions</a></li>
        <li><a href="assign_role.php">Assign Roles to Users</a></li>
        <li><a href="manage_questions.php">Manage Question Bank</a></li>
        <li><a href="manage_quizzes.php">Manage Quizzes/Tests</a></li>
        <li><a href="system_settings.php">System Settings</a></li> 
        
    </ul>
</div>

<?php

include_once __DIR__ . '/../includes/footer.php'; 
?>
