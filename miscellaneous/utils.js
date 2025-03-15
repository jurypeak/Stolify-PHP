// When the fa-fa-eye icon is clicked, the password field will toggle between text and password types.
function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
// When the logo on pages after login is clicked, the user will be redirected to the albums page.
function logoOnClick() {
    window.location.href = 'albums.php';
}
// When the account icon is clicked, the user will be redirected to the account page.
function accountOnClick() {
    window.location.href = 'account.php';
}