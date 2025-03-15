$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault();

        const username = $('input[name="email"]').val();
        const password = $('input[name="password"]').val();
        // If either the username or password fields are empty, display an error message using Swal.fire.
        if (!username || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Both fields are required!',
            });
            return;
        }
        // Send an AJAX request to login.php with the username and password as data. And receive a JSON response
        // from login.php.
        // https://stackoverflow.com/questions/6009206/what-is-ajax-and-how-does-it-work
        // https://sweetalert2.github.io/
        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: {
                username: username,
                password: password
            },
            dataType: 'json',
            // If the response status is 'success', display a success message using Swal.fire and redirect the user to
            // the albums page after the user clicks the OK button.
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: response.message,
                    }).then(() => {
                        window.location.href = 'albums.php';
                    });
                    // If the response status is not 'success', display an error message using Swal.fire.
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            // If an error occurs while processing the request, display an error message using Swal.fire.
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
