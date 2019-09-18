function updatePageRoute()
{
    var pageTitle = $( "#title" ).val();
    pageTitle = convertToSlug(pageTitle);
    $( "#route" ).val(pageTitle);

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
    updatePageRoute();
});

function getThumb(url)
{
    var resultUrl = url.substring(0, url.lastIndexOf("/") + 1);
    var filename = url.substring(url.lastIndexOf('/')+1);
    parts = filename.split(".");
    var thumb = parts[0]+'_64x64px.'+parts[1];
    var thumburl = resultUrl+'_thumbs/'+thumb;

    return thumburl;
}
//# sourceMappingURL=pageCreate.js.map
