document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    
    form.addEventListener("submit", function (event) {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        
        // Prevent client-side filtering that blocks SQL injection attempts
        if (!username || !password) {
            alert("Please enter both username and password.");
            event.preventDefault();
        }
        
        // Debugging logs (Remove in production)
        console.log("Submitting: ", { username, password });
    });
});
