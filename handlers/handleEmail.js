$(document).ready(function() {
    $('#resetPasswordForm').submit(function(e) {
        e.preventDefault();

        const email = $('#email').val();
        const submitButton = $('input[type="submit"]').prop('disabled', true).val('Sending...');
        // Send an AJAX request to forgotPassword.php with the email as data. And receive a JSON response from forgotPassword.php.
        $.post('pages/forgotPassword.php', { email }, function(response) {
            submitButton.prop('disabled', false).val('Send Link');
            // If the response status is 'success', display a success message using Swal.fire.
            Swal.fire({
                icon: response.status === 'success' ? 'success' : 'error',
                title: response.status === 'success' ? 'Success!' : 'Error!',
                text: response.message,
                timer: 3000,
                showConfirmButton: false,
                backdrop: 'rgba(0, 0, 0, 0.5)'
            });
        }, 'json')
            // If an error occurs while processing the request, display an error message using Swal.fire.
            .fail(function() {
                submitButton.prop('disabled', false).val('Send Link');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue sending your request. Please try again.',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: 'rgba(0, 0, 0, 0.5)'
                });
            });
    });
});
