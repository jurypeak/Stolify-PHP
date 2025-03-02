function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '<i class="fa fa-eye"></i>';
    }
}

function logoOnClick() {
    window.location.href = 'albums.php';
}