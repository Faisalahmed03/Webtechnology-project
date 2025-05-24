
<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
  header("Location: admin_login.php");
  exit();
}
include_once '../Controller/admin_panel.php';
?>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin-panel</title>
  <link rel="stylesheet" href="../Public/admin_panel.css" />
</head>
<body>

<form method="post" action="">
  <div>
    <h2>User Management</h2>
    <p>Manage user records and perform actions.</p>
    <button type="submit" name="filter-users">Filter</button>
    <button type="submit" name="view">View All Information</button>
    <button type="submit" name="update-info">Update Information</button>
  </div>
<div id="output">
  <?php
  
    echo $filterOutput;
    echo $updateOutput;
    echo $viewOutput;
  ?>
</div>
  <div>
    <h2>Content Moderation</h2>
    <p>Review and manage user-submitted content.</p>
    <button type="submit" name="filter-content">Filter</button>
    <button type="submit" name="bulk-content">Bulk Actions</button>
  </div>

  <div>
    <h2>System Settings</h2>
    <p>Access and configure advanced system options.</p>
    <button type="submit" name="advanced-settings">Advanced Configuration</button>
  </div>
</form>


</body>
</html>
