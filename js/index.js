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
    if (labType === 'xss') {
        setTimeout(() => {
            window.location.href = 'challenges/xss/xss.html';
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
    if (labType === 'network') {
        setTimeout(() => {
            window.location.href = 'theory/network.html';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
    if (labType === 'packet') {
        setTimeout(() => {
            window.location.href = 'theory/packet.html';
        }, 2000);
    } else {
        setTimeout(() => {
            deployBtn.textContent = 'Lab Ready!';
            deployBtn.style.background = '#2ecc71';
        }, 2000);
    }
}

function submitFlag() {
    const flagInput = document.getElementById('flag-input');
    const result = document.getElementById('flag-result');
    if (flagInput.value.toLowerCase() === 'flag{test}') {
        result.textContent = '✅ Correct! Challenge completed!';
        result.style.color = '#2ecc71';
    } else {
        result.textContent = '❌ Incorrect flag. Try again!';
        result.style.color = '#e74c3c';
    }
}