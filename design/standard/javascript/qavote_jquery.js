(function( $ )
    {
        $(document).ready( function()
        {
    
            $('.vote-up-off').click(_rate);
    
            $('.vote-down-off').click(_rate);
    
        /*
        $('ul.qavote-star-rating').each( function(){
         
            var node = $( this );
            if ( !node.hasClass('qavote-star-rating-disabled') )
                   node.addClass('qavote-star-rating-enabled');
        });
        $('ul.qavote-star-rating-enabled li a').click( _rate );
         */
        });

        function _rate( e )
        {
            

            e.preventDefault();
            var args = $(this).attr('id').split('_');
            if(!$(this).hasClass('qavote-disabled') ){
                if(args[3]==1){
                    $(this).removeClass('vote-up-off');
                    $(this).addClass('vote-up-on');
                } else {
                    $(this).removeClass('vote-down-off');
                    $(this).addClass('vote-down-on');            
                }
                $('li a', '#qavote_rating_' + args[1]).unbind( 'click' );
                jQuery.ez( 'dappsocial::rate::' + args[1] + '::' + args[2] + '::' + args[3], {}, _callBack );
            } else {
                $('#qavote_has_rated_' + args[1]).removeClass('hide');
            }
            return false;
        }

        function _callBack( data )
        {

            if ( data && data.content !== '' )
            {
                if ( data.content.rated )
                {
                    if ( data.content.already_rated )
                        $('#qavote_changed_rating_' + data.content.id).removeClass('hide');
                    else
                        $('#qavote_just_rated_' + data.content.id).removeClass('hide');
                    $('#qavote_count_' + data.content.id).text( data.content.stats.vote_up - data.content.stats.vote_down );
                    $('#qavote_total_' + data.content.id).text( data.content.stats.vote_count );
                }
                else if ( data.content.already_rated )
                    $('#qavote_has_rated_' + data.content.id).removeClass('hide');
            //else alert('Invalid input variables, could not rate!');
            }
            else
            {
                // This shouldn't happen as we have already checked access in the template..
                // Unless this is inside a aggressive cache-block of course.
                alert( data.content.error_text );
            }
        }
    })(jQuery);
