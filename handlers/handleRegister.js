$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();  // Prevent the default form submission behavior

        const username = $('#username').val();
        const password = $('#password').val();

        // Validate input
        if (!username || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Username and password are required.',
                timer: 3000,
                showConfirmButton: false,
                backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
            });
            return;
        }

        // Disable the submit button and change the text
        const submitButton = $('input[type="submit"]');
        submitButton.prop('disabled', true).val('Registering...');

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: 'register.php',  // The PHP script to handle registration
            data: {username: username, password: password},  // Data to send
            dataType: 'json',  // Expecting JSON response
            success: function(response) {
                console.log(response); // Log the response for debugging

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Register');

                if (response.status === 'success') {
                    // Display success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                    }).then(function() {
                        // Redirect to login page after successful registration
                        window.location.href = 'login.php';
                    });
                } else {
                    // Display error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error: ", error);

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Register');

                // Show a general error notification if AJAX fails
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue with the registration. Please try again later.',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                });
            }
        });
    });
});
