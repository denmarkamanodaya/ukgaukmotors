<div class="modal fade" id="user-sign-in" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h4>User Sign in</h4>
                <div class="cs-login-form">
                    <form action="/auth/login" method="post">
                        {!! csrf_field() !!}
                        <div class="input-holder">
                            <label for="cs-username-1"> <strong>Email</strong> <i class="icon-user-plus2"></i>
                                <input type="email" class="" id="email" name="email" placeholder="Email Address">
                            </label>
                        </div>
                        <div class="input-holder">
                            <label for="cs-login-password-1"> <strong>Password</strong> <i class="icon-unlock40"></i>
                                <input type="password" id="password" name="password" placeholder="******">
                            </label>
                        </div>
                        @if(Settings::get('recaptcha_login') && Settings::get('recaptcha_site_key') != '')
                            <div id="recaptcha2"></div>
                        @endif
                        <div class="input-holder"> <a class="btn-forgot-pass" data-dismiss="modal" data-target="#user-forgot-pass" data-toggle="modal" href="javascript:;" aria-hidden="true"><i class=" icon-question-circle"></i> Forgot password?</a> </div>
                        <div class="input-holder">
                            <input class="cs-color csborder-color" type="submit" value="SIGN IN">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="cs-user-signup"> <i class="icon-user-plus2"></i> <strong>Not a Member yet? </strong> <a class="cs-color" data-dismiss="modal" data-target="#user-sign-up" data-toggle="modal" href="javascript:;" aria-hidden="true">Signup Now</a> </div>
            </div>
        </div>
    </div>
</div>

