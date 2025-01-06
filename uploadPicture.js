const fileInput = document.getElementById('file-input');
const uploadButton = document.getElementById('change-photo');
const preview = document.getElementById('profile-picture');

// Trigger the file input when the button is clicked
uploadButton.addEventListener('click', () => {
    fileInput.click();
});

// Display the selected image as a preview
fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = (event) => {
            preview.src = event.target.result;
        };

        reader.readAsDataURL(file);
    }
});