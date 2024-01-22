$(document).ready(function () {
    $('.like').on('click', function (event) {
        event.preventDefault();
        const commentId = $(this).closest('.like-form').data('comment-id');
        const postId = $(this).closest('.like-form').data('post-id');
        
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Jesteś pewny, że chcesz polubić/odlub ten komentarz?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Tak, polub/odlub komentarz",
            cancelButtonText: "Nie, anuluj",

            customClass: {
                confirmButton: 'btn btn-success styled-button',
                cancelButton: 'btn btn-danger styled-button'
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "POST",
                    url: `/api/posts/${postId}/comments/${commentId}/like`,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                }).done(function (data) {
                    if (data.status === 'success') {
                        swalWithBootstrapButtons.fire(
                            'Sukces!',
                            'Komentarz został polubiony/odlubiony.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", "Wystąpił błąd podczas polubienia/odlubienia.", "error");
                    }
                }).fail(function (data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});
