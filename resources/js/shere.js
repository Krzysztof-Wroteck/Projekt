$(document).ready(function () {
    $('.shere').on('click', function (event) {
        event.preventDefault();

        const postId = $(this).data('id');
        const shereButton = $(this);
        const currentScroll = $(window).scrollTop();

        $.ajax({
            method: 'POST',
            url: '/posts/list/' + postId,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function (data) {
            if (data.status === 'success') {
                const sheresCountElement = shereButton.find('.sheres-count');
                if (sheresCountElement) {
                    sheresCountElement.text(data.sheresCount + ' sheres');
                }
            } else {
                Swal.fire("Error", "Wystąpił błąd podczas sherowania.", "error");
            }
        })
        .fail(function (error) {
            Swal.fire("Error", "Wystąpił błąd podczas sherowania.", "error");
        })
        .always(function () {
            window.scrollTo(0, currentScroll);
            location.reload();
        });
    });
});
