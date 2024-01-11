$(document).ready(function () {
    $('.like-form').on('submit', function (event) {
        event.preventDefault();

        const form = $(this);
        const postId = form.data('post-id');
        const likeButton = form.find('.like-button');
        
        $.ajax({
            method: 'POST',
            url: '/api/posts/list/' + postId,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function(data) {
            if (data.status === 'success') {
                const likesCountElement = likeButton.find('.likes-count'); 
                if (likesCountElement) {
                    likesCountElement.text(data.likesCount + ' likes');
                }
            } else {
                alert("1 Wystąpił błąd podczas polubienia/odlubienia.");
            }
        })
        .fail(function(error) {
            alert("2 Wystąpił błąd podczas polubienia/odlubienia.");
        })
        .always(function() {
            location.reload();  
        });
    });
});