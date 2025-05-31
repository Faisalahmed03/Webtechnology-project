document.getElementById('signup-form').addEventListener('submit', function (e) {
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();
  const confirmPassword = document.getElementById('confirm-password').value.trim();

  const nameError = document.getElementById('nameerror');  
  const passwordError = document.getElementById('passworderror');

  
  nameError.textContent = "";
  passwordError.textContent = "";

  let valid = true;


  if (username.length < 2) {
    nameError.textContent = "Username must be at least 2 characters long.";
    valid = false;
  }
  if (password.length < 4) {
    passwordError.textContent = "Password must be at least 4 characters.";
    valid = false;
  } else if (password !== confirmPassword) {
    passwordError.textContent = "Passwords do not match.";
    valid = false;
  }

  if (!valid) {
    e.preventDefault();
  }
});
