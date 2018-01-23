$(document).ready(function(){
    var articleTag;
    var checkboxArticleTag;
    var id;
    var published;

    function updateArticleTag(checkboxArticleTag, response){
        checkboxArticleTag.prop('checked', response.data);
    }

    function ajaxCall(url, checkboxArticleTag, id, published, successCallbackFunction){
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: {id: id, published: published},
            cache: false,
            success: function(response){
                response = jQuery.parseJSON(response);

                if (String(response.status) === 'Okay'){
                    successCallbackFunction(checkboxArticleTag, response);
                }

                else{
                    if ($('.alert').length == 1){
                        $('.alert').remove();
                    }

                    $('body').prepend('<div class="alert alert-danger alert-dismissable"><a class="close" href="#" aria-label="close" data-dismiss="alert">X</a><strong>' + String(response.status) + '</strong></div>');
                    $('.alert').css('display', 'block');
                    $('.alert').animate({top: '75px'}, 'slow');

                    if (checkboxArticleTag.prop('checked') == 'true'){
                        checkboxArticleTag.prop('checked', true);
                    }

                    else{
                        checkboxArticleTag.prop('checked', false);
                    }

                    console.error(String(response.status));
                }
            },
            error: function(request){
                console.error('Erreur : ' + String(request.responseText));
            }
        })
    }

    $('input[type="checkbox"]').on('change', function(){
        articleTag = $(this).closest('article');
        id = articleTag.data('id');
        checkboxArticleTag = $(this);
        published = $(this).prop('checked');
        ajaxCall(updateArticleTagURL, checkboxArticleTag, id, published, updateArticleTag);
    });
});
