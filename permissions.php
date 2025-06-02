<?php
include_once '../includes/functions.php';
session_start();
require_login();
require_role('admin');



$roles = [
    'admin' => 'Full access to all system features, including user management and system settings.',
    'teacher' => 'Can create and manage questions, quizzes, view student results, and manage their classes.',
    'student' => 'Can take quizzes, view their results, and manage their profile.',
    
];

$permissions_map = [
    
    'manage_users' => ['admin'],
    'manage_roles' => ['admin'],
    'manage_question_bank' => ['admin', 'teacher'],
    'create_quizzes' => ['admin', 'teacher'],
    'take_quizzes' => ['student'],
    'view_own_results' => ['student'],
    'view_all_results' => ['admin', 'teacher'],
    'generate_certificates' => ['admin', 'teacher'],
    
];


include_once '../includes/header.php';
?>

<h2>Role-Based Access Control & Permissions</h2>
<?php display_message(); ?>

<p>This section outlines the different user roles and their typical permissions within the system. Actual enforcement happens in code via checks like <code>has_role('admin')</code> or more granular permission checks if implemented.</p>

<h3>Defined Roles:</h3>
<ul>
    <?php foreach ($roles as $role => $description): ?>
        <li><strong><?php echo htmlspecialchars(ucfirst($role)); ?>:</strong> <?php echo htmlspecialchars($description); ?></li>
    <?php endforeach; ?>
</ul>

<h3>Feature Permissions (Conceptual):</h3>
<p>Below is a conceptual mapping of features to roles that typically have access. This is not an active settings panel but for informational purposes in this basic setup.</p>
<ul>
    <?php foreach ($permissions_map as $feature => $allowed_roles): ?>
        <li><strong><?php echo htmlspecialchars(str_replace('_', ' ', ucfirst($feature))); ?>:</strong> Accessible by <?php echo htmlspecialchars(implode(', ', array_map('ucfirst', $allowed_roles))); ?></li>
    <?php endforeach; ?>
</ul>

<p>In a more advanced system, you would have a UI here to:
    <ul>
        <li>Create/Edit/Delete Roles</li>
        <li>Define specific permissions (e.g., "create_question", "edit_user", "view_reports")</li>
        <li>Assign permissions to roles</li>
    </ul>
</p>

<p><a href="index.php">Back to Admin Dashboard</a></p>


<?php include_once '../includes/footer.php'; ?>
