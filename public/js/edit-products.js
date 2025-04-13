 // Enhance image preview interactivity
 document.getElementById('image').addEventListener('change', function(e) {
    const uploadText = document.getElementById('upload-text');
    const imagePreview = document.getElementById('image-preview');
    const file = e.target.files[0];

    if (file) {
        uploadText.classList.add('hidden');
        imagePreview.classList.remove('hidden');
        imagePreview.src = URL.createObjectURL(file);
    }
});