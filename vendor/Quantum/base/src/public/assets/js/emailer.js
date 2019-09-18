$('#user').change(function() {
    $('input:checkbox').removeAttr('checked');
    $('#allUsers').attr('checked', false);
});

$("input[name='roles[]']").change(function() {
    $('#user').val(0);
    $('#allUsers').attr('checked', false);
});


$('#allUsers').change(function() {

    $("input[name='roles[]']").each(function ()
    {
        $(this).removeAttr('checked');
    });

    $('#user').val(0);

});

//# sourceMappingURL=emailer.js.map
