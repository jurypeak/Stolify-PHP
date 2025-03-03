$(document).ready(function() {
    // Listen for form submit
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        // Get form data
        const username = $('#username').val();
        const password = $('#password').val();

        // Disable the button and change text to "Logging In..."
        const submitButton = $('input[type="submit"]');
        submitButton.prop('disabled', true).val('Logging In...');

        // Perform AJAX request to server for authentication
        $.ajax({
            type: 'POST',
            url: 'login.php',  // Your PHP script to handle login
            data: { username: username, password: password },  // Sending form data
            dataType: 'json',  // Expecting JSON response
            success: function (response) {
                console.log(response);  // Log the response for debugging

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Log In');

                if (response.status === 'success') {
                    // Display success notification and then redirect
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: response.message,
                        timer: 3000,  // Set how long the notification will appear (3000 ms = 3 seconds)
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                    }).then(function() {
                        // After the success notification is closed, redirect to the albums page
                        window.location.href = 'albums.php'; // Example redirection
                    });
                } else {
                    // Display error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                    });
                }
            },
            error: function (xhr, status, error) {
                // Log error if AJAX request fails
                console.log("AJAX Error: ", error);

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Log In');

                // Show error notification using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue logging you in. Please try again.',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                });
            }
        });
    });
});
