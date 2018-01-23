$(document).ready(function(){
    var commentTag;
    var id;
    var text;

    function updateCommentTag(commentTag, response){
        commentTag.children('.comment_text').text(String(response.data));
    }

    function deleteCommentTag(commentTag, response){
        commentTag.addClass('delete');

        setTimeout(function(){
            commentTag.remove();

            setTimeout(function(){
                if ($('.comment').length == 0){
                    $('#comments').append('<article class="comment well"><p id="comment_text_alone">Il n\'y a pas encore de commentaire sur mes articles...</p></article>');
                }
            }, 50);
        }, 400);
    }

    function ajaxCall(url, commentTag, id, text, successCallbackFunction){
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: {id: id, text: text},
            cache: false,
            success: function(response){
                response = jQuery.parseJSON(response);

                if (String(response.status) === 'Okay'){
                    successCallbackFunction(commentTag, response);
                }

                else{
                    if ($('.alert').length == 1){
                        $('.alert').remove();
                    }

                    $('body').prepend('<div class="alert alert-danger alert-dismissable"><a class="close" href="#" aria-label="close" data-dismiss="alert">X</a><strong>' + String(response.status) + '</strong></div>');
                    $('.alert').css('display', 'block');
                    $('.alert').animate({top: '75px'}, 'slow');
                    console.error(String(response.status));
                }
            },
            error: function(request){
                console.error('Erreur : ' + String(request.responseText));
            }
        })
    }

    $('.comment_text').dblclick(function(){
        $(this).prop('contenteditable', 'true');
        $(this).focus();
    });

    $('.comment_text').blur(function(){
        $(this).prop('contenteditable', 'false');
    });

    $('button').click(function(){
        commentTag = $(this).closest('.comment');
        id = commentTag.data('id');

        if ($(this).hasClass('btn-primary')){
            text = commentTag.children('.comment_text').text();
            ajaxCall(updateCommentTagURL, commentTag, id, text, updateCommentTag);
        }

        else if ($(this).hasClass('btn-danger')){
            text = null;
            ajaxCall(deleteCommentTagURL, commentTag, id, text, deleteCommentTag);
        }

        $(this).blur();
    });
});
