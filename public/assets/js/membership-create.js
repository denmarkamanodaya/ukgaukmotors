
function selectTypeValue()
{
    var membershipType = $( "#type" ).val();

    if(membershipType == 'free') {
        $('.paid_membership_inputs').hide();
        $('.free_membership_inputs').show();
    }
    if(membershipType == 'paid') {
        $('.paid_membership_inputs').show();
        $('.free_membership_inputs').hide();
    }
}

function selectSubscriptionValue()
{
    if($('#expires').is(":checked")) {
        $('.subscription-inputs').show();
    } else {
        $('.subscription-inputs').hide();
    }
}


$('#type').on('change', function (e) {
    selectTypeValue();
});

$('#expires').on('change', function (e) {
    selectSubscriptionValue();
});

CKEDITOR.replace( 'description', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
} );

selectTypeValue();
selectSubscriptionValue();

