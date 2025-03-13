$(document).ready(function() {
    $('#registrationForm').on('submit', function(event) {
        event.preventDefault();

        const username = $('input[name="email"]').val();
        const password = $('input[name="password"]').val();

        if (!username || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Both fields are required!',
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'register.php',
            data: {
                username: username,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: response.message,
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request. Please try again.',
                });
            }
        });
    });
});
