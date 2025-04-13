document.getElementById('image').addEventListener('change', function(e) {
    let file = e.target.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('image-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('upload-text').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('price_display').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    let number = parseInt(value) || 0;
    if (value !== '') {
        this.value = new Intl.NumberFormat('id-ID').format(number);
    }
    document.getElementById('price').value = number;
});

document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('price').value = document.getElementById('price_display')
        .value.replace(/\D/g, '');
});