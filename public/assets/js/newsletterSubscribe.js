$(document).ready(function() {

    // process the form
    $('#newsletter_subscribe').submit(function(event) {
        $('.form-group').removeClass('has-error'); // remove the error class
        $('.help-block').remove(); // remove the error text
        $('#subscribe_error_wrap').hide();

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
                if (errors.first_name) {
                    $('#first_name_group').addClass('has-error'); // add the error class to show red input
                    $('#first_name_group').append('<div class="help-block">' + errors.first_name + '</div>'); // add the actual error message under our input
                }

                // handle errors for email ---------------
                if (errors.last_name) {
                    $('#last_name_group').addClass('has-error'); // add the error class to show red input
                    $('#last_name_group').append('<div class="help-block">' + errors.last_name + '</div>'); // add the actual error message under our input
                }

                // handle errors for superhero alias ---------------
                if (errors.email) {
                    $('#email-group').addClass('has-error'); // add the error class to show red input
                    $('#email-group').append('<div class="help-block">' + errors.email + '</div>'); // add the actual error message under our input
                }
        })
        // success function
            .success(function(data) {
                    if (data.success) {
                    $('#newsletter_subscribe_form').html(data.successHtml);

                    // window.location = '/thank-you'; // redirect a user to another page
                    } else {
                        $('#subscribe_errors').html(data.errorMsg);
                        $('#subscribe_error_wrap').show();
                    }
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

});