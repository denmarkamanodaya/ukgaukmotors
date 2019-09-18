$('#addFeedMake').submit(function(event) {
    console.log('submit');
    event.preventDefault();
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");

    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            dataType: "json",
            success:function(data, textStatus, jqXHR)
            {
                showMessage(data["type"], data["message"]);
                if(data["type"] == 'success'){
                    $('.addFeedMakeSubmit').toggle();
                    $('.FeedMakeFound').toggle();
                }
            }
        });

});

$('#addFeedMakeModel').submit(function(event) {
    console.log('submit');
    event.preventDefault();
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");

    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            dataType: "json",
            success:function(data, textStatus, jqXHR)
            {
                showMessage(data["type"], data["message"]);
                if(data["type"] == 'success'){
                    $('.addFeedMakeModelSubmit').toggle();
                    $('.FeedMakeModelFound').toggle();
                }

            }
        });

});

$('#addFeedAuctioneer').submit(function(event) {
    console.log('submit');
    event.preventDefault();
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");

    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            dataType: "json",
            success:function(data, textStatus, jqXHR)
            {
                showMessage(data["type"], data["message"]);
                if(data["type"] == 'success'){
                    $('.addFeedAuctioneerSubmit').toggle();
                    $('.FeedAuctioneerFound').toggle();
                }

            }
        });

});


function showMessage(ntype, nmessage){
    var icon = ''
    if(ntype == 'success')
    {
        icon = 'fa  fa-check-circle';
    }
    if(ntype == 'error')
    {
        ntype = 'danger';
        icon = 'fa  fa-times-circle';
    }
    $.notify({
        // options
        icon: icon,
        message: '&nbsp;'+nmessage
    },{
        // settings
        type: ntype,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        }
    });

}

$('.addFeedMakeSubmit').click(function(event) {
    event.preventDefault();
    $('#addFeedMake').submit();
});
$('.addFeedMakeModelSubmit').click(function(event) {
    event.preventDefault();
    $('#addFeedMakeModel').submit();
});
$('.addFeedAuctioneerSubmit').click(function(event) {
    event.preventDefault();
    $('#addFeedAuctioneer').submit();
});