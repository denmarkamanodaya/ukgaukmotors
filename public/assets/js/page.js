$('#required_roles').hide();

function selectValue()
{
    var area = $( "#page-area" ).val();

    if(area == 'public') {
        $('#required_roles').hide();
        $('#route-area').html('/');
    }
    if(area == 'members') {
        $('#required_roles').show();
        $('#route-area').html('/members/');
        $("#robots").val("noindex, nofollow");
    }
    if(area == 'admin') {
        $('#required_roles').show();
        $('#route-area').html('/admin/');
        $("#robots").val("noindex, nofollow");
    }
}


$('#page-area').change(function() {
    selectValue();
});

CKEDITOR.replace( 'content', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 1000
} );

CKEDITOR.replace( 'preContent', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 1000
} );


// File Picker modification for FCK Editor v2.0 - www.fckeditor.net
// by: Pete Forde <pete@unspace.ca> @ Unspace Interactive
var urlobj;

function BrowseServer(obj)
{
    urlobj = obj;
    OpenServerBrowser(
        baseUrl+'/filemanager/index.html',
screen.width * 0.7,
screen.height * 0.7 ) ;
}

function OpenServerBrowser( url, width, height )
{
    var iLeft = (screen.width - width) / 2 ;
    var iTop = (screen.height - height) / 2 ;
    var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
    sOptions += ",width=" + width ;
    sOptions += ",height=" + height ;
    sOptions += ",left=" + iLeft ;
    sOptions += ",top=" + iTop ;
    var oWindow = window.open( url, "BrowseWindow", sOptions ) ;
}

function SetUrl( url, width, height, alt )
{
    document.getElementById(urlobj).value = url ;
    oWindow = null;
    var thumbnail = getThumb(url)
    $('#thumbnail_featured_image').html('<img src="'+thumbnail+'">');
    $('#featured_image_preview_wrap').show();
}

function getThumb(url)
{
    var resultUrl = url.substring(0, url.lastIndexOf("/") + 1);
    var filename = url.substring(url.lastIndexOf('/')+1);
    parts = filename.split(".");
    var thumb = parts[0]+'_64x64px.'+parts[1];
    var thumburl = resultUrl+'_thumbs/'+thumb;

    return thumburl;
}

function setThumb()
{
    var featureUrl = $('#featured_image').val();
    if(featureUrl !='')
    {
        $("#thumbnail_featured_image").attr("src",featureUrl);
        $('#featured_image_preview_wrap').show();
        $('#featured_image_remove').show();
    } else {
        $("#thumbnail_featured_image").attr("src",'')
        $('#featured_image_remove').hide();
    }
}

$('#featured_image_remove').click(function(){
   $('#featured_image').val('');
    $("#thumbnail_featured_image").attr("src",'');
    $('#featured_image_remove').hide();
});


$('#featured_image').change(function(e){
    setThumb();
});


selectValue();
setThumb();

//# sourceMappingURL=page.js.map
