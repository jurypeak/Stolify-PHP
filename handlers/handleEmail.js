$(document).ready(function() {
    $('#resetPasswordForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        const email = $('#email').val();

        // Disable the button and change text to "Sending..."
        const submitButton = $('input[type="submit"]');
        submitButton.prop('disabled', true).val('Sending...');

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: 'pages/forgotPassword.php',  // Your PHP script
            data: {email: email},  // Sending the email data
            dataType: 'json',  // Expecting JSON response
            success: function (response) {
                console.log(response);  // Log the response for debugging

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Send Link');

                if (response.status === 'success') {
                    // Display success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
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
            error: function (xhr, status, error) {
                // Log error if AJAX request fails
                console.log("AJAX Error: ", error);

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Send Link');

                // Show error notification for any type of error during the request
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue sending your request. Please try again.',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                });
            }
        });
    });
});