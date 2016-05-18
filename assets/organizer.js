$(document).ready(function () {
    STUDIP.organizer.init();
});
STUDIP.organizer = {
    init: function() {
        $('.organizer_auto_form').change(function() {
            $(this).parentsUntil('.organizer_ajax_replace').hide();
            $.ajax({
                url     : $(this).attr('action'),
                type    : $(this).attr('method'),
                data    : $(this).serialize(),
                datatype: 'html',
                success : function( data ) {
                    $('#layout_content').html($(data));
                    STUDIP.organizer.init();
                }
            });
        }).find("button, input[type='submit']").hide();

        $('.organizer_auto_link').click(function(e) {
            e.preventDefault();
            $(this).addClass('disabled');
            var link = $(this).attr('href');
            $.ajax({
                url     : link,
                datatype: 'html',
                success : function( data ) {
                    $('#layout_content').html($(data));
                    STUDIP.organizer.init();
                }
            });
        });

        $('.organizer_useradd').hide();

        $('.organizer_user_drag').draggable({
            cursor: "move",
            revert:true
        });

        $( ".organizer_group_drop" ).droppable({
            accept: ".organizer_user_drag",
            activeClass: "organizer_drop_possible",
            hoverClass: "organizer_drop_hover",
            drop: function( event, ui ) {
                var draggable = ui.draggable;
                var user_id = draggable.data().user_id;
                var group_id = $(this).data().group_id;
                var form = $('.organizer_add_to_group_form').first();
                draggable.hide();
                $.ajax({
                    url     : form.attr('action'),
                    type    : form.attr('method'),
                    data    : {'user_id': user_id, 'group_id': group_id},
                    datatype: 'html',
                    success : function( data ) {
                        $('#layout_content').html($(data));
                        STUDIP.organizer.init();
                    }
                });
            }
        });

        $( ".organizer_user_drop" ).droppable({
            accept: ".organizer_user_drag",
            activeClass: "organizer_drop_possible",
            hoverClass: "organizer_drop_hover",
            drop: function( event, ui ) {
                var draggable = ui.draggable;
                var user1 = draggable.data().user_id;
                var user2 = $(this).data().user_id;
                var form = $('.organizer_new_group').first();
                draggable.hide();
                $(this).hide();
                $.ajax({
                    url     : form.attr('action'),
                    type    : form.attr('method'),
                    data    : {'user1': user1, 'user2': user2},
                    datatype: 'html',
                    success : function( data ) {
                        $('#layout_content').html($(data));
                        STUDIP.organizer.init();
                    }
                });
            }
        });
    }
}