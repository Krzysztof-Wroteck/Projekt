$(document).ready(function () {
    $('.shere').on('click', function (event) {
        event.preventDefault();
        const postId = $(this).closest('.shere-form').find('input[name="post_id"]').val(); // Poprawione pobieranie post_id
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Are you sure you want to share/unshare this post?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, share/unshare this post!",
            cancelButtonText: "No",
            customClass: {
                confirmButton: 'btn btn-success styled-button',
                cancelButton: 'btn btn-danger styled-button'
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "POST",
                    url: '/api/posts/shere/' + postId,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                }).done(function (data) {
                    if (data.status === 'success') {
                        swalWithBootstrapButtons.fire(
                            'Sukces!',
                            'this post  has been share/unshare.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", "An error occurred while  share/unshare.", "error");
                    }
                }).fail(function (data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});
