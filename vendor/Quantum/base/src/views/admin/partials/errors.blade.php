@if(count($errors) > 0)
    <script>
        var arrayLength = formErrors.length;
        for (var i = 0; i < arrayLength; i++) {
            $( "#"+formErrors[i] ).closest('.form-group').addClass( "has-error" );
        }
        $.notify({
            // options
            icon: '{{ notif_image('danger') }}',
            message: 'There was a problem with your input'
        },{
            // settings
            type: 'danger',
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            }
        });
    </script>

@endif