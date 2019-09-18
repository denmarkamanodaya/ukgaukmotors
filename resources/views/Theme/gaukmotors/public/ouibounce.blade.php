<style>

    #ouibounce-modal .ouimodal {
        width: 100% !important;
        max-width: 800px;
        height: 513px !important;
        background-color: #000000;
        z-index: 105;
        position: absolute;
        margin: auto;
        top: 50% !important;
        right: 0;
        bottom: 0;
        left: 50% !important;
        border-radius: 4px;
        -webkit-animation: popin .3s;
        animation: popin .3s;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    #ouibounce-modal .ouimodal-body {
        font-size: 0.9em;
        padding: 0px !important;
        display: flex;
        flex-direction: row-reverse;
        border: 3px solid rgba(218,218,218,1);
    }

    .ouimodal-message {
        width: 100%;
        padding: 20px;
        color: #cccccc;
    }
    .ouimodal-message h1 {
        color: #cccccc !important;
    }
    #newsletter_subscribe_form_oui {
        margin-top: 40px;
    }
    .ouimodal-message p {
        color: #cccccc !important;
    }

</style>
<div id="ouibounce-modal">
    <div class="underlay">
        &nbsp;</div>
    <div class="ouimodal">

        <div class="ouimodal-body">

                <figure class="wph-modal--image" style="max-height: 510px;">
                    <img src="{!! url('images/alerts.jpg') !!}">
                </figure>


                <div class="ouimodal-message" style="max-height: 656.85px;">
                    <h1 class="wph-modal--title">DON'T MISS OUT!</h1>
                    <div id="newsletter_subscribe_form_oui">
                        <form id="newsletter_subscribe_oui" accept-charset="UTF-8" action="/newsletter/subscribe/gauk-auctions" method="POST">
                            <div id="subscribe_error_wrap" class="row" style="display: none;">
                                <div id="subscribe_errors" class="col-md-12">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div id="first_name_group" class="col-md-12">
                                    <div class="form-group"><label class="control-label" for="first_name">First Name</label><br> <input id="first_name" class="form-control" name="first_name" type="text" value=""></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="last_name_group" class="form-group"><label class="control-label" for="last_name">Last name</label><br> <input id="last_name" class="form-control" name="last_name" type="text" value=""></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="email_group" class="form-group"><label class="control-label" for="email">Email Address</label><br> <input id="email" class="form-control" name="email" required="required" type="email" value=""></div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="cs-field-holder text-center mt-20">
                                    <div class="cs-btn-submit">
                                        <input value="Subscribe" type="submit">
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <p>We never spam!</p>

                </div>

        </div>
    </div>
</div>

