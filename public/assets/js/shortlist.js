$(document).ready(function(){
    $(document).on('click', '.short-list', function(e){
        var urlTo = $(this).attr('href');
        var shortlist = this;
        e.preventDefault();
        $.ajax(
            {
                url : urlTo,
                type: "POST",
                data : { _token: token}
            }).done(function(data) {
            if(data.type == 'add') {
                $(shortlist).find('i').removeClass('icon-heart-o');
                $(shortlist).find('i').addClass('icon-heart');
            }
            if(data.type == 'remove') {
                $(shortlist).find('i').removeClass('icon-heart');
                $(shortlist).find('i').addClass('icon-heart-o');
            }
            $.notify({
                // options
                icon: 'fa  fa-check-circle',
                message: '&nbsp;'+data.message
        },{
                // settings
                type: 'success',
                    animate: {
                    enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                }
            });
        });


    });
});