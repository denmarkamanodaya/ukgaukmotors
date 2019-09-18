<div class="modal fade" id="user-sign-up" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h4>Create Account</h4>
                <div class="cs-login-form">
                    {!! Form::open(array('method' => 'POST', 'url' => 'register', 'autocomplete' => 'off')) !!}
                        <div class="input-holder">
                            <label for="cs-username"> <strong>USERNAME</strong> <i class="icon-user-plus2"></i>
                                <input type="text" class="" id="username" name="username" placeholder="Type desired username" required>
                            </label>
                        </div>
                        <div class="input-holder">
                            <label for="cs-email"> <strong>Email</strong> <i class="icon-envelope"></i>
                                <input type="email" class="" id="email" name="email" placeholder="Type desired email address" required>
                            </label>
                        </div>
                        <div class="input-holder">
                            <label for="password"> <strong>Password</strong> <i class="icon-unlock40"></i>
                                <input type="password" id="password" name="password" placeholder="******" required>
                            </label>
                        </div>
                        <div class="input-holder">
                            <label for="password_confirmation"> <strong>confirm password</strong> <i class="icon-unlock40"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="******" required>
                            </label>
                        </div>

                    @if(Settings::get('register_first_name'))
                        <div class="input-holder">
                            <label for="first_name"> <strong>First Name</strong> <i class="icon-unlock40"></i>
                                <input type="text" id="first_name" name="first_name" placeholder="" required>
                            </label>
                        </div>
                    @endif

                    @if(Settings::get('register_last_name'))
                        <div class="input-holder">
                            <label for="last_name"> <strong>Last Name</strong> <i class="icon-unlock40"></i>
                                <input type="text" id="last_name" name="last_name" placeholder="" required>
                            </label>
                        </div>
                    @endif

                    @if(Settings::get('recaptcha_register') && Settings::get('recaptcha_site_key') != '')
                        <div id="recaptcha3"></div>
                    @endif


                        <div class="input-holder">
                            <input class="cs-color csborder-color" type="submit" value="Create Account">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"> <a data-dismiss="modal" data-target="#user-sign-in" data-toggle="modal" href="javascript:;" aria-hidden="true">Already have account</a>

            </div>
        </div>
    </div>
</div>

