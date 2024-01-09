$(function () {
    $('.shere').on('click', function (e) {
        e.preventDefault();
        const postId = $(this).data('id');
        const $button = $(this);

        $.ajax({
            method: 'POST',
            url: '/shere/' + postId,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function (data) {
            if (data.action === 'shared') {
                $button.addClass('shared');
                $button.find('.fa').removeClass('fa-regular').addClass('fa-solid');
            } else {
                $button.removeClass('shared');
                $button.find('.fa').removeClass('fa-solid').addClass('fa-regular');
            }

            $button.find('.shere-count').text(data.sheresCount);
        }).fail(function (data) {
            console.error(data);
        });
    });
});
