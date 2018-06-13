$(function () {

    $('.contact-add').on('click', function (e) {
        var target = $(this).data('target');
        var a = $(this);

        $.ajax({
            method: 'POST',
            url: target
        }).done(function() {
            a.removeClass('fa-plus').addClass('fa-check');
        });
    });

    $('.contact-delete').on('click', function (e) {
        var target = $(this).data('target');
        var a = $(this);

        $.ajax({
            method: 'DELETE',
            url: target
        }).done(function() {
            a.closest('tr').remove();
        });
    });

});