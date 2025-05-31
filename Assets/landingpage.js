function toggleLoginOptions() {
  const loginOptions = document.getElementById('login-options');
  loginOptions.style.display = loginOptions.style.display === 'block' ? 'none' : 'block';
  document.getElementById('create-options').style.display = 'none';
}

function toggleCreateOptions() {
  const createOptions = document.getElementById('create-options');
  createOptions.style.display = createOptions.style.display === 'block' ? 'none' : 'block';
  document.getElementById('login-options').style.display = 'none';
}
