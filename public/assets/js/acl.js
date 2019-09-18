function updateRoleSlug()
{
    var itemTitle = $( "#role-create #title" ).val();
    slug = convertToSlug(itemTitle);
    $( "#role-create #name" ).val(slug);

}

function updateRoleEditSlug()
{
    var itemTitle = $( "#role-edit #title" ).val();
    slug = convertToSlug(itemTitle);
    $( "#role-edit #name" ).val(slug);

}

function updatepermissionSlug()
{
    var itemTitle = $( "#permission-create #title" ).val();
    slug = convertToSlug(itemTitle);
    $( "#permission-create #name" ).val(slug);

}


function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'');
}


$('#role-create #title').keyup(function() {
    updateRoleSlug();
});

$('#role-edit #title').keyup(function() {
    updateRoleEditSlug();
});

$('#permission-create #title').keyup(function() {
    updatepermissionSlug();
});
