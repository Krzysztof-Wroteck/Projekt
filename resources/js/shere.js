$(function () {
    $('.shere').on('click', function (e) {
        e.preventDefault();
        const postId = $(this).data('id');

        $.ajax({
            method: 'POST',
            url: '/shere/' + postId,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function (data) {
            if (data.action === 'shared') {
                $(e.target).addClass('shared');
            } else {
                $(e.target).removeClass('shared');
            }

            $(e.target).find('.shere-count').text(data.sheresCount);
        }).fail(function (data) {
            console.error(data);
        });
    });
});
