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

$(document).ready(function() {
    var formmodified=0;
    var posted=0;

    window.onbeforeunload = confirmExit;
    function confirmExit() {
        checkCkeditor();

        if(posted == 0)
        {
            if (formmodified == 1) {
                return "New information not saved. Do you wish to leave the page?";
            }
        }

    }
    function checkCkeditor() {
        for (var i in CKEDITOR.instances) {
            if(CKEDITOR.instances[i].checkDirty())
            {
                formmodified = 1;
            }
        }
    }

    $("#PostManage").on("submit", function(){
        posted=1;
    });
    $("input, select").change(function() {
        formmodified=1;
    });
});
//# sourceMappingURL=pageCreate.js.map
