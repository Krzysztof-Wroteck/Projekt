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
            title: "Jesteś pewny, że chcesz polubić/oblud ten post?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Tak, polub/odlub komentarz",
         cancelButtonText: "Nie, anuluj",
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
                            'Post został polubiony/odlubić.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", "Wystąpił błąd podczas polubienia.", "error");
                    }
                }).fail(function (data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});
