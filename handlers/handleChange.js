$(document).ready(function() {
    $('#accountForm').on('submit', function(e) {
        e.preventDefault();

        const username = $('input[name="email"]').val();
        const password = $('input[name="password"]').val();

        const submitButton = $('#accountForm button[type="submit"]');
        submitButton.prop('disabled', true).val('Changing...');

        // Send an AJAX request to account.php with the username and password as data. And receive a JSON response from account.php.
        $.ajax({
            type: 'POST',
            url: 'account.php',
            data: {
                accountForm: 1,
                username: username,
                password: password,
            },
            dataType: 'json',
            // If the response status is 'success', display a success message using Swal.fire and
            // redirect the user to the account page after the user clicks the OK button.
            success: function(response) {
                console.log(response);

                submitButton.prop('disabled', false).val('Change Details');

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'
                    }).then(function() {
                        window.location.href = 'account.php';
                    });
                    // If the response status is not 'success', display an error message using Swal.fire.
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'
                    });
                }
            },
            // If an error occurs while processing the request, display an error message using Swal.fire.
            error: function(xhr, status, error) {
                console.log("AJAX Error: ", error);

                submitButton.prop('disabled', false).val('Change Details');

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue with changing the account details. Please try again later.',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: 'rgba(0, 0, 0, 0.5)'
                });
            }
        });
    });
});
