<?php include_once '../includes/header.php'; ?>
<h2>Email Verification</h2>
<div class="message <?php echo $msg_type; ?>">
    <?php echo htmlspecialchars($message); ?>
</div>
<p><a href="../public/index.php?controller=login">Go to Login</a></p>
<?php include_once '../includes/footer.php'; ?>
