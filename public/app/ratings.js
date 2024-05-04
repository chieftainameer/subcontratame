jQuery(document).ready(function(){
    $('.ratings_user').starrr({
        readOnly: true,
        rating: $('.ratings_user').data('rating-user')
    });
})