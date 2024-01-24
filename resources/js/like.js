$(document).ready(function () {
    $('.like').on('click', function (event) {
        event.preventDefault();
        const postId = $(this).closest('.like-form').find('input[name="post_id"]').val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success styled-button',
                cancelButton: 'btn btn-danger styled-button'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Are you sure you want to like/dislike this post??",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, like/dislike this post",
         cancelButtonText: "No",
            customClass: {
                confirmButton: 'btn btn-success styled-button',
                cancelButton: 'btn btn-danger styled-button'
            },
            reverseButtons: true,
            buttonsStyling: false,
           
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "POST",
                    url: '/api/posts/list/' + postId,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                }).done(function (data) {
                    if (data.status === 'success') {
                        swalWithBootstrapButtons.fire(
                            'Sukces!',
                            'this post has been liked/unliked',
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
