$(document).ready(function() {
    $('#accountForm').on('submit', function(e) {
        e.preventDefault();

        const username = $('input[name="email"]').val();
        const password = $('input[name="password"]').val();


        const submitButton = $('#accountForm button[type="submit"]');
        submitButton.prop('disabled', true).val('Changing...');

        $.ajax({
            type: 'POST',
            url: 'account.php',
            data: {
                accountForm: 1,
                username: username,
                password: password,
            },
            dataType: 'json',
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
