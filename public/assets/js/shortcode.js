function updateShortcode()
{
    var pageTitle = $( "#title" ).val();
    pageTitle = convertToSlug(pageTitle);
    $( "#shortcode" ).val(pageTitle);
    $( "#name" ).val(pageTitle);

}

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}


$('#title').keyup(function() {
    updateShortcode();
});