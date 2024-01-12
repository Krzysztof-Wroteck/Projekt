$(document).ready(function () {
    $('.delete').on('click', function () {
        const postId = $(this).data('id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Jesteś pewny, że chcesz usunąć ten post?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Tak",
            cancelButtonText: "Nie",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "DELETE",
                    url: '/api/posts/list/' + postId,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                }).done(function (data) {
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        Swal.fire("Error", "Wystąpił błąd podczas usuwania.", "error");
                    }
                }).fail(function (data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});