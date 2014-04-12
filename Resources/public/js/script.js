$(function($){
    // Loader
    $('#ns-loader').css('opacity', 0);

    // Sortables
    $(".table-sortable tbody").each(function(i, tbody){
        // ajax-handler check
        if (!$(tbody).parent().attr('data-sortable-handler')) {
            throw "Required attribute 'data-sortable-handler' wasn't found";
        }

        $(tbody).sortable({
            'helper': function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },
            'axis': 'y',
            'stop': function(e, ui){
                // checking id attribute
                if (!ui.item.attr('data-sortable-id')) {
                    throw "Required attribute 'data-sortable-id' wasn't found";
                }
                // saving position
                $('#ns-loader').animate({'opacity': 1});
                $.ajax({
                    'url': $(tbody).parent().attr('data-sortable-handler'),
                    'data': {
                        'id': +ui.item.attr('data-sortable-id'),
                        'position': $(tbody).find('tr').index(ui.item)
                    }
                })
                    .done(function(res){
                        if (res && typeof(res.error) != 'undefined') {
                            throw res.error;
                        }
                    })
                    .always(function(){
                        $('#ns-loader').animate({'opacity': 0});
                    });
            }
        }).disableSelection();
    });

    // Confirm buttons
    $('.ns-confirm').click(function(){
        if (confirm('Вы уверены?')) {
            location.href = $(this).attr('href');
        }
        return false;
    });

    // Login window
    $('.ns-login').css({top:'40%',opacity:0});
    setTimeout(function(){
        $('.ns-login').animate({top:'50%',opacity:1}, 300);
    }, 600);
    $('.ns-login button[type=submit]').click(function(){
        $('.ns-login').animate({top:'40%',opacity:0}, 300, function(){$('.ns-login').submit();});
        return false;
    });
});