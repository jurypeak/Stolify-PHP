$(function () {
    $('#deleteAccountForm').submit(function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "This action is irreversible. Your account will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
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

