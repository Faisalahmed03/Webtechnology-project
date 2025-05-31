<?php
include '../Model/teacher_db.php';
include '../Controller/contact_control.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <link rel="stylesheet" href="../Assets/contact.css">
</head>
<body>

<h1>Contact Us</h1>

<?php if (empty($successMessage)): ?>
<form onsubmit="return validateForm()" method="post" action="">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name">
  <div id="nameError" class="error"></div>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email">
  <div id="emailError" class="error"></div>

  <label for="message">Message:</label>
  <textarea id="message" name="message" rows="5"></textarea>
  <div id="messageError" class="error"></div>

  <label for="captcha">What is <?= $a ?> + <?= $b ?>?</label>
  <input type="text" id="captcha" name="captcha">
  <div id="captchaError" class="error"></div>

  <input type="hidden" name="captcha_a" value="<?= $a ?>">
  <input type="hidden" name="captcha_b" value="<?= $b ?>">

  <button type="submit">Submit</button>
  <button type="button" onclick="window.location.href='landingpage.php'">Back</button>

</form>
<?php endif; ?>

<script src="../Assets/contact.js"></script>
<script>
<?php if (!empty($successMessage)): ?>
    alert("<?= $successMessage ?>");
    window.location.href = "contact.php"; 
<?php elseif (!empty($errorMessage)): ?>
    alert("<?= $errorMessage ?>");
<?php endif; ?>
</script>

</body>
</html>