function clicksubmitbutton(event) {
    event.preventDefault(); 

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const msg = document.getElementById("msg");


    if (username === "" || password === "") {
        msg.textContent = "Please enter both username and password.";
        msg.style.color = "red";
        return false;
    }

    if (username === "admin" && password === "12345") {
        msg.textContent = `Welcome, ${username}!`;
        msg.style.color = "green";
    } else {
        msg.textContent = "Invalid username or password.";
        msg.style.color = "red";
    }

    return false;
}
