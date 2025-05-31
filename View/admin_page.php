
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
  <link rel="stylesheet" href="../Assets/admin_panel.css" />
</head>
<body>

<form method="post" action="">
   <div class="search-bar">
    <h2>Search</h2>
    <form id="search-form">
      <input type="text"class="searchbar" id="search-input" placeholder="Type to search...">
      <button type="submit" id="search-button">Search</button>
      <button type="submit" name="filter-users">Filter</button>
   
   <section>
      <?php
    echo $filterOutput;

  ?>
   </section>
  </div>

  <div>

    <h2>User Management</h2>
    <p>Manage user records and perform actions.</p>

    <button type="submit" name="view">View All Information</button>
    <button type="submit"name="update-info">Update Information</button>
    <section>
  <?php
    echo $viewOutput;
    echo $updateOutput;
  ?>
  </section>

 </div>

   <button type="button"  onclick="window.location.href='landingpage.php'" name="Back">Back</button>
</form>


</body>
</html>
