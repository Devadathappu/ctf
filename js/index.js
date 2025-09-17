function togglePath(id) {
    const details = document.getElementById(`path-details-${id}`);
    const allDetails = document.querySelectorAll('.path-details');
    allDetails.forEach(d => {
        if (d.id !== `path-details-${id}`) {
            d.style.display = 'none';
        }
    });
    details.style.display = details.style.display === 'block' ? 'none' : 'block';
}

function deployLab(labType) {
    const deployBtn = event.target;
    deployBtn.textContent = 'Deploying...';
    
    // If it's the XSS lab, navigate to xss.html after "deployment"
    if (labType === 'intro') {
        setTimeout(() => {
            window.location.href = 'intro.html';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
    
    if (labType === 'xss') {
        setTimeout(() => {
            window.location.href = 'challenges/xss/index.php';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }

    if (labType === 'file') {
        setTimeout(() => {
            window.location.href = 'challenges/file_upload/file.html';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
    if (labType === 'sqli') {
        setTimeout(() => {
            window.location.href = 'challenges/sqli/login_sqli.html';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
    if (labType === 'idor') {
        setTimeout(() => {
            window.location.href = 'challenges/idor/login_idor.html';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
    if (labType === 'data') {
        setTimeout(() => {
            window.location.href = 'challenges/sensitive_data/phpinfo.php';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
}

function submitFlag() {
    const flagInput = document.getElementById("flag-input").value;
    const resultDiv = document.getElementById("flag-result");

    // Example correct flag (replace with actual flag)
    const correctFlag = "flag{file_upload_pwned}";

    if (flagInput === correctFlag) {
        resultDiv.innerHTML = '<p style="color: green;">✅ Correct! You captured the flag!</p>';
    } else {
        resultDiv.innerHTML = '<p style="color: red;">❌ Incorrect flag. Try again!</p>';
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const terminalOutput = document.getElementById("terminal-output");
    const text = terminalOutput.innerText;
    terminalOutput.innerText = ""; // Clear existing text for effect
    
    let i = 0;
    function typeEffect() {
        if (i < text.length) {
            terminalOutput.innerHTML += text.charAt(i);
            i++;
            setTimeout(typeEffect, 50); // Adjust speed of typing effect
        }
    }
    typeEffect();
});
