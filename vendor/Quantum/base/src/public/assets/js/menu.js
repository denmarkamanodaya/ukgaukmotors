$('.itemWorking').hide();

$('.dd').nestable({
    dropCallback: function(details) {
        showMenuItem(details.sourceId);
    },

    onDragStart: function(l,e){
        // l is the main container
        // e is the element that was moved
        showMenuItem(e[0].id);
        //console.log(l);
        //console.log(e[0].id);
    }
});

var showMenuItem = function(itemId) {
    $('.itemDetails').hide();
    $('.itemWorking').show();
    $.ajax(
        {
            url : getItemUrl,
            type: "POST",
            data : { itemId: itemId, _token: token, menu: menu}
        }).done(function(data){
        if(data.status == 'success')
        {
            $('.itemDetails').html(data.data);
        }
        $('.itemWorking').hide();
        $('.itemDetails').show();
    });
};


$("#item-order").submit(function(e)
{

    var formURL = $(this).attr("action");
    var values = {};
    $.each($(this).serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });
    values['position'] =  JSON.stringify($('.dd').nestable('serialize'));

    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : values
        }).done(function(data){
        if(data.status == 'success')
        {
            message = {
                'text': 'Menu item position has been saved.',
                'type': 'success',
                'title': 'Success',
                'icon': 'far fa-check'
            };
            setMessage(message);
        }
        if(data.status == 'error')
        {
            message = {
                'text': 'There was a problem saving the order.',
                'type': 'error',
                'title': 'Error',
                'icon': 'far fa-times'
            };
            setMessage(message);
        }

    });

    e.preventDefault();
});

$('#pageSelect').change(function() {
    var route = $('#pageSelect option:selected').val();
    var pagetitle = $('#pageSelect option:selected').text();
    if(route != 0){
        $('#url').val(route);
        $('input[name="title"]').val(pagetitle);
    }
});
//# sourceMappingURL=menu.js.map
