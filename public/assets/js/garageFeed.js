$( document ).ready(function() {


loadAllFeed();

    $("a1").click(function(event) {
        event.preventDefault();
        console.log('clicked');
        return false;
    });

    $(document).on('click', '.loadMore', function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        var feedId = $(this).closest('div.feedCol').attr('data-feedId');
        getFeed(feedId, url);
        return false;
    })

});


function loadAllFeed() {
    $( ".feedCol" ).each(function( index ) {
        var search = $(this).attr('data-search');
        var feedId = $(this).attr('data-feedId');
        getFeed(feedId, feedUrl);
    });
}

function getFeed(feedId, url) {
    $.ajax(
        {
            url : url,
            type: "POST",
            data : { _token: token, feed: feedId}
        }).done(function(data) {
        $( "#feedBody_"+feedId ).append( data );

    });

}