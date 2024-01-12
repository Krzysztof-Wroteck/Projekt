$(document).ready(function() {
    $('.like-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const postId = form.data('post-id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            method: "POST",
            url: '/api/posts/like/' + postId,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).done(function(data) {
            if (data.status === 'success') {
                const likesCountElement = form.find('.likes-count');
                const currentLikesCount = parseInt(likesCountElement.text().split(' ')[0]);
                likesCountElement.text((currentLikesCount + 1) + ' likes');
            } else {
                Swal.fire("Error", "Wystąpił błąd podczas dodawania polubienia.", "error");
            }
        }).fail(function(data) {
            Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
        });
    });
});