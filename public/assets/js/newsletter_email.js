CKEDITOR.replace( 'html_message', {
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
    height: 1000,
} );
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;

CKEDITOR.on( 'instanceReady', function( ev )
{
    var editor = ev.editor,
        dataProcessor = editor.dataProcessor,
        htmlFilter = dataProcessor && dataProcessor.htmlFilter;

    // Output properties as attributes, not styles.
    htmlFilter.addRules(
        {
            elements :
                {
                    $ : function( element )
                    {
                        // Output dimensions of images as width and height
                        if ( element.name == 'img' )
                        {
                            var style = element.attributes.style;

                            if ( style )
                            {
                                // Get the width from the style.
                                var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec( style ),
                                    width = match && match[1];

                                // Get the height from the style.
                                match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec( style );
                                var height = match && match[1];

                                if ( width )
                                {
                                    element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
                                    element.attributes.width = width;
                                }

                                if ( height )
                                {
                                    element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
                                    element.attributes.height = height;
                                }
                            }
                        }

                        if ( !element.attributes.style )
                            delete element.attributes.style;

                        return element;
                    }
                }
        });

});

$(document).ready(function() {

    // process the form
    $('#template_choice').submit(function(event) {

        var postData = $(this).serialize()
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
                    CKEDITOR.instances['html_message'].setData(data.content);
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

    function personaliseValue()
    {
        console.log('bcc');
        if($('#personalise').is(":checked")) {
            $('.bcc-inputs').hide();
        } else {
            $('.bcc-inputs').show();
        }
    }


    $('#personalise').on('change', function (e) {
        personaliseValue();
    });

    personaliseValue();

});