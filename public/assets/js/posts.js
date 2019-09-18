$('#required_roles').hide();
$('#publishTimeSelect').hide();

function selectValue()
{
    var area = $( "#post-area" ).val();

    if(area == 'public') {
        $('#required_roles').hide();
    }
    if(area == 'members') {
        $('#required_roles').show();
        $("#robots").val("noindex, nofollow");
    }
    if(area == 'admin') {
        $('#required_roles').hide();
        $("#robots").val("noindex, nofollow");
    }
    if(area == 'private') {
        $('#required_roles').hide();
        $("#robots").val("noindex, nofollow");
    }
}

function selectPublishValue()
{
    var pubtime = ( $("#publishOnTime").is(':checked') ) ? 1 : 0;
    if(pubtime == 1) {
        $('#publishTimeSelect').show();
    } else {
        $('#publishTimeSelect').hide();
    }
}


$('#post-area').change(function() {
    selectValue();
});

$('#publishOnTime').change(function() {
    selectPublishValue();
});



CKEDITOR.replace( 'content', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 1000
} );

CKEDITOR.replace( 'summary', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    wordcount: {
        maxWordCount: '100'
    },
    height: 1000
} );

function updateSlug()
{
    var itemTitle = $( "#PostManage #title" ).val();
    slug = convertToSlug(itemTitle);
    $( "#PostManage #slug" ).val(slug);

}

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'');
}

$('#PostManage #title').keyup(function() {
    updateSlug();
});

$('#featured_image_remove').click(function(){
    $('#featured_image').val('');
    $('#featured_image_preview_wrap').hide();
});

$('#featured_image_preview_wrap').hide();
selectValue();
selectPublishValue();


function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
    $('textarea').trigger('keyup');
}

function autosave()
{
    CKupdate();
    var form = $('#PostManage');
    var action = form.attr('action');
    action = action + '/autoSave';
    $.post( action, form.serialize(), function( data ) {
        console.log(data);
        if(data == "saved") sendNotify('info', 'A draft copy has been saved.');
    });
}

function sendNotify(level,umessage) {
    $.notify({
        // options
        icon: '',
        message: umessage
},{
        // settings
        type: level,
            animate: {
            enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
        }
    });
}

var repeatTime = 60000; //1 min
$(document).ready(function () {
    if(repeatTime > 0) setInterval(autosave, repeatTime);
});
//# sourceMappingURL=posts.js.map
