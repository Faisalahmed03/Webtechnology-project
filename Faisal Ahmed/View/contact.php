<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <link rel="stylesheet" href="../Public/contact.css">

</head>
<body>

  <h1>Contact Us</h1>

<form onsubmit="return validateForm()">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name">
    <div id="nameError" class="error"></div>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <div id="emailError" class="error"></div>

    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="5"></textarea>
    <div id="messageError" class="error"></div>

    <?php
    session_start();
    $a = rand(1, 10);
    $b = rand(1, 10);
    $_SESSION['captcha_answer'] = $a + $b;
    ?>
    <label for="captcha">What is <?= $a ?> + <?= $b ?>?</label>
    <input type="text" id="captcha" name="captcha">
    <div id="captchaError" class="error"></div>

    <button type="submit">Submit</button>
  </form>

  <script src ="../Controller/contact.js"></script>
</body>
</html>
