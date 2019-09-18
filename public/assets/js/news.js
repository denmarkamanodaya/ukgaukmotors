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

CKEDITOR.replace( 'header_content', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 300
} );

CKEDITOR.replace( 'content', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 1000
} );

selectValue();