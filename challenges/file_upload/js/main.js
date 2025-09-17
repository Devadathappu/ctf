document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadList = document.getElementById('uploadList'); // Ensure this element exists in your HTML if you use it.
    const uploadStatus = document.getElementById('uploadStatus'); // Ensure this element exists in your HTML if you use it.

    // Create a span element to display the selected file name.
    const fileNameDisplay = document.createElement('span');
    fileNameDisplay.className = 'file-name-display';
    fileNameDisplay.style.display = 'none'; // Hidden by default.
    dropZone.appendChild(fileNameDisplay);

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
        if (e.dataTransfer.files.length > 0) {
            fileNameDisplay.textContent = `Selected: ${e.dataTransfer.files[0].name}`;
            fileNameDisplay.style.display = 'block';
        }
        handleFiles(e.dataTransfer.files);
    });

    // Update file name display when a file is selected via the file input
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            fileNameDisplay.textContent = `Selected: ${e.target.files[0].name}`;
            fileNameDisplay.style.display = 'block';
        }
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            uploadFile(file);
        });
    }

    function uploadFile(file) {
        const formData = new FormData();
        // Use the same key name as expected by upload.php:
        formData.append('fileInput', file);
    
        const fileItem = createFileListItem(file);
        if (uploadList) {
            uploadList.appendChild(fileItem);
        }
    
        fetch('php/upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fileItem.querySelector('.file-status').textContent = 'Uploaded';
                fileItem.querySelector('.file-status').style.color = '#2ed573';
                showStatus('File uploaded successfully!', 'success');
                // Only set preview if a valid URL is returned
                if (data.fileUrl) {
                    const img = document.createElement('img');
                    img.src = data.fileUrl;
                    img.alt = "Uploaded file preview";
                    // Clear previous preview and add new image
                    if (uploadList) {
                        uploadList.innerHTML = '';
                        uploadList.appendChild(img);
                    }
                }
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
        if (uploadStatus) {
            uploadStatus.textContent = message;
            uploadStatus.className = 'upload-status ' + type;
            uploadStatus.style.display = 'block';
            setTimeout(() => {
                uploadStatus.style.display = 'none';
            }, 5000);
        }
    }
});

// A separate function for flag checking if needed.
function checkFlag() {
    const flag = document.getElementById('flag-input').value;
    // Adjust the flag value as needed for your challenge.
    if (flag.toLowerCase() === 'flag{file_upload_master}') {
        alert('Congratulations! You have completed the challenge!');
    } else {
        alert('Incorrect flag. Try again!');
    }
}
