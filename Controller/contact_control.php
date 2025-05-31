<?php

$successMessage = "";
$errorMessage = "";
$isFormSubmitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($isFormSubmitted) {
    $captcha_a = (int)($_POST['captcha_a'] ?? 0);
    $captcha_b = (int)($_POST['captcha_b'] ?? 0);
    $user_captcha = (int)($_POST['captcha'] ?? -1);

    if ($user_captcha !== ($captcha_a + $captcha_b)) {
        $errorMessage = "CAPTCHA verification failed.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            $errorMessage = "All fields are required.";
        } else {
            $stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);

            if ($stmt->execute()) {
                $successMessage = "Thank you! Your message has been sent.";
            } else {
                $errorMessage = "Database error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

if (!$isFormSubmitted || !empty($errorMessage)) {
    $a = rand(1, 10);
    $b = rand(1, 10);
}

$testimonial = null;
$sql = "SELECT name, message FROM contact ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $testimonial = $result->fetch_assoc();
}

$conn->close();
?>