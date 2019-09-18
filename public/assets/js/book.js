function setThumb()
{
    var featureUrl = $('#featured_image').val();
    if(featureUrl !='')
    {
        $("#thumbnail_featured_image").attr("src",featureUrl);
        $('#featured_image_preview_wrap').show();
    } else {
        $('#featured_image_remove').hide();
    }
}

$('#featured_image_remove').click(function(){
    $('#featured_image').val('');
    $("#thumbnail_featured_image").attr("src",'');
});

function setThumbFront()
{
    var featureUrl = $('#front_cover').val();
    if(featureUrl !='')
    {
        $("#thumbnail_front_cover").attr("src",featureUrl);
        $('#front_cover_preview_wrap').show();
    } else {
        $('#front_cover_remove').hide();
    }
}

$('#front_cover_remove').click(function(){
    $('#front_cover').val('');
    $("#front_cover_image").attr("src",'');
});

function setThumbRear()
{
    var featureUrl = $('#back_cover').val();
    if(featureUrl !='')
    {
        $("#thumbnail_back_cover").attr("src",featureUrl);
        $('#back_cover_preview_wrap').show();
    } else {
        $('#back_cover_remove').hide();
    }
}

$('#back_cover_remove').click(function(){
    $('#back_cover').val('');
    $("#back_cover_image").attr("src",'');
});

setThumb();
setThumbFront();
setThumbRear();
$('#lfm').filemanager('image', {prefix: '/filemanager'});
$('#lfm2').filemanager('image', {prefix: '/filemanager'});
$('#lfm3').filemanager('image', {prefix: '/filemanager'});


CKEDITOR.replace( 'content', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    enterMode : CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
    extraAllowedContent: 'style;*[id,rel](*){*}',
    disableNativeSpellChecker: false,
    scayt_autoStartup: true,
    height: 400
} );

CKEDITOR.replace( 'preContent', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    enterMode : CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
    extraAllowedContent: 'style;*[id,rel](*){*}',
    disableNativeSpellChecker: false,
    scayt_autoStartup: true,
    height: 400
} );

CKEDITOR.replace( 'details', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    enterMode : CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
    extraAllowedContent: 'style;*[id,rel](*){*}',
    disableNativeSpellChecker: false,
    scayt_autoStartup: true,
    height: 1000
} );

CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.on('dialogDefinition', function(ev) {
    var editor = ev.editor;
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if (dialogName == 'image') {
        var advancedTab = dialogDefinition.getContents( 'advanced' );
        var classField = advancedTab.get( 'txtGenClass' );
        classField['default'] = 'thumbnail col-xs-11 col-sm-4'; // Add class

        var infoTab = dialogDefinition.getContents( 'info' );
        var field = infoTab.get('txtAlt');

        var txtUrl = infoTab.get( 'txtUrl' ); // Now fill the url link into Link Tab
        txtUrl['onMyEvent'] = txtUrl['onChange']; // Save current event onChange to use later because if not then the new onChange function will remove the old event and got an error into the functionality of the editor
        txtUrl['onChange'] = function(evt){
            var dialog = CKEDITOR.dialog.getCurrent();
            $(this).trigger( 'onMyEvent' ); // Fire the saved event and continue fill data
            dialog.getContentElement('Link', 'txtUrl').setValue(dialog.getContentElement('info', 'txtUrl').getValue());
            dialog.getContentElement('info', 'txtHSpace').setValue(10);
            dialog.getContentElement('info', 'txtVSpace').setValue(10);
            dialog.getContentElement('info', 'cmbAlign').setValue('left');
        }
    }
});