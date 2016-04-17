/*-------------------------------------------------------------------------------------------------
Like a post
-------------------------------------------------------------------------------------------------*/
$('.like-buttons').click(function(event) {
    $.ajax({
        type: 'POST',
        url: '/home',
        success: function(response) {
        var idName = "#like-count-" + event.target.id;
        console.log(idName); 
            $(idName).html(response);
        },
        data: {
            type: 'like',
            post: event.target.id
        }
    }); 
});

/*-------------------------------------------------------------------------------------------------
Unlike a post
-------------------------------------------------------------------------------------------------*/
$('.unlike-buttons').click(function(event) {
    $.ajax({
        type: 'POST',
        url: '/home',
        success: function(response) {
        var idName = "#like-count-" + event.target.id;
        console.log(idName); 
            $(idName).html(response);
        },
        data: {
            type: 'unlike',
            post: event.target.id
        }
    }); 
});

/*-------------------------------------------------------------------------------------------------
Comment on a post
-------------------------------------------------------------------------------------------------*/
$('.commentField').keypress(function(event) {
    var key = event.which;
    if(key == 13){
        var id = event.target.id.substring(8);
        $.ajax({
            type: 'POST',
            url: '/home',
            success: function(response) {
                console.log(response);
                $(response).insertBefore($('#commentArea'));
            },
            data: {
                type: 'comment',
                post: id,
                input: document.getElementById(event.target.id).value
            }
        });
    }
});