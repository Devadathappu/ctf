document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    // 🕵️ Hidden credentials in JavaScript for the challenge
    const hardcodedUsername = "john";
    const hardcodedPassword = "12345@";

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    if (username === hardcodedUsername && password === hardcodedPassword) {
      // Encode user ID (1234 -> MTIzNA==)
      let userId = btoa("1234");
      window.location.href = "profile.php?id=" + encodeURIComponent(userId);
    } else {
      document.getElementById("error").textContent = "Invalid username or password!";
    }
  });