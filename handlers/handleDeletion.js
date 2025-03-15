$(function () {
    $('#deleteAccountForm').submit(function (e) {
        e.preventDefault();
        // Display a confirmation dialog using Swal.fire.
        // https://sweetalert2.github.io/
        Swal.fire({
            title: 'Are you sure?',
            text: "This action is irreversible. Your account will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
            // If the user clicks the 'Yes, delete it!' button, send an AJAX request to account.php with the 'delete-account'
            // parameter set to 1. And receive a JSON response from account.php.
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.post('account.php', { 'delete-account': 1 })
                .done(response => {
                    console.log("Server response:", response);
                    if (typeof response !== 'object') {
                        try {
                            response = JSON.parse(response);
                        } catch (error) {
                            console.error('Invalid JSON response:', response);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Unexpected server response. Please try again.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            return;
                        }
                    }

                    // If the response status is 'success', display a success message using Swal.fire and redirect the user to
                    // the login page after the user clicks the OK button.
                    const isSuccess = response.status === 'success';

                    Swal.fire({
                        icon: isSuccess ? 'success' : 'error',
                        title: isSuccess ? 'Deleted!' : 'Error!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        if (isSuccess) window.location.href = 'login.php';
                    });
                })
                // If an error occurs while processing the request, display an error message using Swal.fire.
                .fail((xhr, status, error) => {
                    console.error("AJAX Error:", status, error);
                    console.log("Server response text:", xhr.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete the account. Please try again later.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
        });
    });
});

