<?php
include_once 'includes/functions.php';
session_start();
require_login(); 

include_once 'includes/header.php';
?>

<h2>Dashboard</h2>
<?php display_message();  ?>

<p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

<div class="dashboard-widgets">
    <div class="widget">
        <h3>Quick Actions</h3>
        <ul>
            <li><a href="start_quiz.php">Start a New Quiz</a></li> // Placeholder
            <li><a href="quiz_history.php">View Quiz History</a></li> // Placeholder
            <li><a href="profile.php">Manage Your Profile</a></li>
        </ul>
    </div>

    <div class="widget">
        <h3>Recent Activity / Stats Overview</h3>
        <p>Your recent quiz scores and progress will appear here.</p>
        
    </div>

    <?php if (has_role('admin') || has_role('teacher')):  ?>
    <div class="widget">
        <h3>Teacher/Admin Tools</h3>
        <ul>
            <li><a href="admin/manage_questions.php">Manage Question Bank</a></li> // Placeholder
            <li><a href="admin/manage_quizzes.php">Manage Quizzes</a></li>       // Placeholder
            <li><a href="admin/view_results.php">View Student Results</a></li>   // Placeholder
            <?php if (has_role('admin')): ?>
                 <li><a href="admin/manage_users.php">Manage Users</a></li> // Placeholder for user management
                 <li><a href="admin/permissions.php">Role Permissions</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>

</div>

<?php include_once 'includes/footer.php'; ?>
