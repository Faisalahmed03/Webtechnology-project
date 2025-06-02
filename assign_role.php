<?php
include_once '../includes/functions.php';
include_once '../includes/db.php';
session_start();
require_login();
require_role('admin');

$users = [];
$roles = ['admin', 'teacher', 'student', 'editor']; // Available roles
$errors = [];
$selected_user_id = '';
$selected_role = '';


$stmt_users = $conn->prepare("SELECT id, username, email, role FROM users ORDER BY username ASC");
if ($stmt_users) {
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt_users->close();
} else {
    $errors[] = "Error fetching users: " . $conn->error;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_user_id = sanitize_input($_POST['user_id']);
    $selected_role = sanitize_input($_POST['role']);

    if (!is_required($selected_user_id)) {
        $errors[] = "Please select a user.";
    }
    if (!is_required($selected_role)) {
        $errors[] = "Please select a role.";
    } elseif (!in_array($selected_role, $roles)) {
        $errors[] = "Invalid role selected.";
    }

    if (empty($errors)) {
        $stmt_update = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        if ($stmt_update === false) {
            $errors[] = "Database error preparing update: " . $conn->error;
        } else {
            $stmt_update->bind_param("si", $selected_role, $selected_user_id);
            if ($stmt_update->execute()) {
                set_message("User role updated successfully!", "success");
                
                header("Location: assign_role.php"); 
                exit();
            } else {
                $errors[] = "Failed to update user role. " . $stmt_update->error;
            }
            $stmt_update->close();
        }
    }
}

$conn->close();
include_once '../includes/header.php';
?>

<h2>Assign Roles to Users</h2>
<?php display_errors($errors); ?>
<?php display_message(); ?>

<form action="assign_role.php" method="post">
    <div>
        <label for="user_id">Select User:</label>
        <select name="user_id" id="user_id" required>
            <option value="">-- Select User --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user['id']); ?>" <?php echo ($selected_user_id == $user['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user['username'] . ' (' . $user['email'] . ') - Current Role: ' . ucfirst($user['role'])); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="role">Select New Role:</label>
        <select name="role" id="role" required>
            <option value="">-- Select Role --</option>
            <?php foreach ($roles as $role_option): ?>
                <option value="<?php echo htmlspecialchars($role_option); ?>" <?php echo ($selected_role == $role_option) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars(ucfirst($role_option)); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <input type="submit" value="Assign Role">
    </div>
</form>

<h3>Current User Roles:</h3>
<?php if (!empty($users)): ?>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Current Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>No users found or error loading users.</p>
<?php endif; ?>


<p><a href="index.php">Back to Admin Dashboard</a></p>

<?php include_once '../includes/footer.php'; ?>
