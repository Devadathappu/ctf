// assets/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadList = document.getElementById('uploadList');
    const uploadStatus = document.getElementById('uploadStatus');

    // Drag and drop handlers
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#6c5ce7';
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgba(108, 92, 231, 0.3)';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgba(108, 92, 231, 0.3)';
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            uploadFile(file);
        });
    }

    function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        // Add file to UI list
        const fileItem = createFileListItem(file);
        uploadList.appendChild(fileItem);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fileItem.querySelector('.file-status').textContent = 'Uploaded';
                fileItem.querySelector('.file-status').style.color = '#2ed573';
                showStatus('File uploaded successfully!', 'success');
            } else {
                fileItem.querySelector('.file-status').textContent = 'Failed';
                fileItem.querySelector('.file-status').style.color = '#ff4757';
                showStatus(data.message, 'error');
            }
        })
        .catch(error => {
            fileItem.querySelector('.file-status').textContent = 'Error';
            fileItem.querySelector('.file-status').style.color = '#ff4757';
            showStatus('Upload failed: ' + error.message, 'error');
        });
    }

    function createFileListItem(file) {
        const item = document.createElement('div');
        item.className = 'file-item';
        item.innerHTML = `
            <div class="file-info">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236c5ce7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z'/%3E%3Cpolyline points='13 2 13 9 20 9'/%3E%3C/svg%3E" class="file-icon">
                <span class="file-name">${file.name}</span>
            </div>
            <span class="file-status">Uploading...</span>
        `;
        return item;
    }

    function showStatus(message, type) {
        uploadStatus.textContent = message;
        uploadStatus.className = 'upload-status ' + type;
        setTimeout(() => {
            uploadStatus.style.display = 'none';
        }, 5000);
    }
});

function checkFlag() {
    const flag = document.getElementById('flag-input').value;
    // You can change this flag value based on your challenge
    if (flag.toLowerCase() === 'flag{file_upload_master}') {
        alert('Congratulations! You have completed the challenge!');
    } else {
        alert('Incorrect flag. Try again!');
    }
}