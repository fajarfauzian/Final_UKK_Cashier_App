 // Toggle password visibility
 document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');

    if (password.type === 'password') {
        password.type = 'text';
        icon.className = 'ri-eye-off-line';
    } else {
        password.type = 'password';
        icon.className = 'ri-eye-line';
    }
});

// Close 
const closeBtn = document.getElementById('closeError');
if (closeBtn) {
    closeBtn.addEventListener('click', () => {
        document.getElementById('errorAlert').style.display = 'none';
    });
    setTimeout(() => document.getElementById('errorAlert').style.display = 'none', 5000);
}
