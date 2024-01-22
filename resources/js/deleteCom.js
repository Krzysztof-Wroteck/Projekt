$(document).ready(function() {
    $('.delete').on('click', function() {
      const postId = $(this).data('post-id');
const commentId = $(this).data('comment-id');
        const swalWithBootstrapButtons = Swal.mixin({
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Jesteś pewny, że chcesz usunąć ten komentarz?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Tak",
            cancelButtonText: "Nie",
            customClass: {
                confirmButton: 'btn btn-success styled-button',
                cancelButton: 'btn btn-danger styled-button'
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
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