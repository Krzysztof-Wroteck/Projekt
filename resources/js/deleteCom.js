$(document).ready(function() {
    $('.delete').on('click', function() {
        const postId = $(this).data('post-id');
        const commentId = $(this).data('comment-id');
        const itemType = $(this).data('type');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        const confirmationMessage = (itemType === 'comment') ? 'Czy na pewno chcesz usunąć ten komentarz?' : 'Czy na pewno chcesz usunąć ten post?';

        swalWithBootstrapButtons.fire({
            title: confirmationMessage,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Tak",
            cancelButtonText: "Nie",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let url;
                if (itemType === 'comment') {
                    url = `'/api/posts/${postId}/comments' + commentId`;
                } else {
                    url = `/api/posts/${postId}`;
                }

                $.ajax({
                    method: "DELETE",
                    url: `/api/posts/${postId}/comments/${commentId}`,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function(data) {
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        Swal.fire("Error", "Wystąpił błąd podczas usuwania.", "error");
                    }
                }).fail(function(data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});
