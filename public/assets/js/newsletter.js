CKEDITOR.replace( 'description', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.replace( 'welcome_email', {
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
    height: 500
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.replace( 'confirmation_email', {
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
    height: 500
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.replace( 'unsubscribe_email', {
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
    height: 500
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.replace( 'subscribed_page', {
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
    height: 500
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.replace( 'unsubscribed_page', {
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
    height: 500
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.replace( 'confirmed_email', {
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
    height: 500
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

var last_repeat = $('#role_change tr.repeat:last').attr('id');
last_repeat = last_repeat.split("-");
last_repeat = parseInt(last_repeat[0]) + 1;
console.log(last_repeat);

jQuery('.repeatable-add').click(function() {
    // Clone row
    $('#role_change tr#0-repeatable').clone(true).appendTo('#role_change');
    $('#role_change tr.repeat:last').removeClass( "no-display");
    $('#role_change tr.repeat:last').attr("id", last_repeat+'-repeatable');
    $('#role_change tr.repeat:last div a').attr("id", last_repeat+'-repeatableid');

    $('#role_change tr.repeat:last td select, #role_change tr.repeat:last td input').each(function(){
        var input = $(this);
        var newattr = input.attr("name");
        var newstr = newattr.replace(/\[(\d+)\]/, '['+last_repeat+']');
        input.attr('name', newstr);
    });
    last_repeat = last_repeat +1;

});

jQuery('.repeatable-del').on('click',function(event) {
    var trid=  $(this).attr( "id" );
    trid = trid.split("-");
    trid = trid[0];
    $("#role_change tr#"+trid+"-repeatable").remove();
});


$(document).ready(function() {

    // process the form
    $('#template_choice').submit(function(event) {

        var postData = $(this).serialize();
        var formURL = $(this).attr("action");


        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : formURL, // the url where we want to POST
            data        : postData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server

        })

            .error(function(data){
                var errors = data.responseJSON;
                console.log(errors);
                // Render the errors with js ...

                // handle errors for name ---------------
                if (errors.newsletter_template) {
                    $('#newsletter_template').addClass('has-error'); // add the error class to show red input
                    $('#newsletter_template').append('<div class="help-block">' + errors.newsletter_template + '</div>'); // add the actual error message under our input
                }
            })
            // success function
            .success(function(data) {
                if (data.success) {
                    CKEDITOR.instances['welcome_email'].setData(data.content);
                    CKEDITOR.instances['confirmation_email'].setData(data.content);
                    CKEDITOR.instances['unsubscribe_email'].setData(data.content);
                    sendSuccessNote();
                }
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

    function sendSuccessNote()
    {
        $.notify({
            // options
            icon: 'fa  fa-check-circle',
        message: 'Template has been loaded'
    },{
        // settings
        type: 'success',
            animate: {
            enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
        }
    });
    }

    function setThumb()
    {

        var featureUrl = $('#featured_image').val();
        console.log('thumb :' +featureUrl);
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


    setThumb();

});
