function validateForm() {
  // Clear previous errors
  document.getElementById('nameError').textContent = '';
  document.getElementById('emailError').textContent = '';
  document.getElementById('messageError').textContent = '';
  document.getElementById('captchaError').textContent = '';

  let isValid = true;

  // Validate Name (not empty)
  const name = document.getElementById('name').value.trim();
  if (name === '') {
    document.getElementById('nameError').textContent = 'Please enter your name.';
    isValid = false;
  }

  // Validate Email (not empty and valid format)
  const email = document.getElementById('email').value.trim();
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === '') {
    document.getElementById('emailError').textContent = 'Please enter your email.';
    isValid = false;
  } else if (!emailPattern.test(email)) {
    document.getElementById('emailError').textContent = 'Please enter a valid email address.';
    isValid = false;
  }

  // Validate Message (not empty)
  const message = document.getElementById('message').value.trim();
  if (message === '') {
    document.getElementById('messageError').textContent = 'Please enter your message.';
    isValid = false;
  }

  // Validate Captcha (not empty and numeric)
  const captcha = document.getElementById('captcha').value.trim();
  if (captcha === '') {
    document.getElementById('captchaError').textContent = 'Please answer the captcha question.';
    isValid = false;
  } else if (!/^\d+$/.test(captcha)) {
    document.getElementById('captchaError').textContent = 'Captcha must be a number.';
    isValid = false;
  }

  return isValid;
}
