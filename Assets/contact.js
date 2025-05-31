function validateForm() {
 
  document.getElementById('nameError').textContent = '';
  document.getElementById('emailError').textContent = '';
  document.getElementById('messageError').textContent = '';
  document.getElementById('captchaError').textContent = '';

  let isValid = true;


  const name = document.getElementById('name').value.trim();
  if (name === '') {
    document.getElementById('nameError').textContent = 'Please enter your name.';
    isValid = false;
  }

  const email = document.getElementById('email').value.trim();
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === '') {
    document.getElementById('emailError').textContent = 'Please enter your email.';
    isValid = false;
  } else if (!emailPattern.test(email)) {
    document.getElementById('emailError').textContent = 'Please enter a valid email address.';
    isValid = false;
  }
  const message = document.getElementById('message').value.trim();
  if (message === '') {
    document.getElementById('messageError').textContent = 'Please enter your message.';
    isValid = false;
  }

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
 