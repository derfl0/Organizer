$(document).ready(function () {
    $('.organizer_auto_form').change(function() {
        $.ajax({
            url     : $(this).attr('action'),
            type    : $(this).attr('method'),
            data    : $(this).serialize()
        });
    }).find("button, input[type='submit']").hide();
});

