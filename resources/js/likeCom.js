$(document).ready(function () {
    $('.like').on('click', function (event) {
        event.preventDefault();
        const commentId = $(this).closest('.like-form').data('comment-id');
        const post = $(this).closest('.like-form').data('post-id');
        
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Are you sure you want to like/dislike this comment?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, like/dislike this comment",
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
                    url: `/api/posts/${post}/comments/${commentId}/like`,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                }).done(function (data) {
                    if (data.status === 'success') {
                        swalWithBootstrapButtons.fire(
                            'success!',
                            'this comment has been liked/unliked.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", "An error occurred while liking/unliking.", "error");
                    }
                }).fail(function (data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});
