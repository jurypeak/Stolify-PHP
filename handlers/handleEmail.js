$(document).ready(function() {
    $('#resetPasswordForm').submit(function(e) {
        e.preventDefault();

        const email = $('#email').val();
        const submitButton = $('input[type="submit"]').prop('disabled', true).val('Sending...');

        $.post('pages/forgotPassword.php', { email }, function(response) {
            submitButton.prop('disabled', false).val('Send Link');

            Swal.fire({
                icon: response.status === 'success' ? 'success' : 'error',
                title: response.status === 'success' ? 'Success!' : 'Error!',
                text: response.message,
                timer: 3000,
                showConfirmButton: false,
                backdrop: 'rgba(0, 0, 0, 0.5)'
            });
        }, 'json')
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
