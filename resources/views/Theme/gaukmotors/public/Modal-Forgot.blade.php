
<div class="modal fade" id="user-forgot-pass" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h4>Password Recovery</h4>
                <div class="cs-login-form">
                    <form method="POST" action="/password/email">
                        {!! csrf_field() !!}
                        <div class="input-holder">
                            <label for="email"> <strong>Email</strong> <i class="icon-envelope"></i>
                                <input type="email" class="" id="mail" name="email" placeholder="Your Registered Email Address">
                            </label>
                        </div>
                        @if(Settings::get('recaptcha_password') && Settings::get('recaptcha_site_key') != '')
                            <div id="recaptcha4"></div>
                        @endif
                        <div class="input-holder">
                            <input class="cs-color csborder-color" type="submit" value="Send">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="cs-user-signup"> <i class="icon-user-plus2"></i> <strong>Not a Member yet? </strong> <a href="javascript:;" data-toggle="modal" data-target="#user-sign-up" data-dismiss="modal" class="cs-color" aria-hidden="true">Signup Now</a> </div>
            </div>
        </div>
    </div>
</div>

