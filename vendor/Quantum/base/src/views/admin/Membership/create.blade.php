@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/membership-create.js')}}"></script>


@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Membership' => '/admin/membership', 'Create' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Membership</span> - Create a new membership
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Membership Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/membership/create')) !!}


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                {!! Form::label('title', 'Membership Title', array('class' => 'control-label')) !!}
                                {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                    {!!inputError($errors, 'title')!!}
                                </div>
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}
                                {!! Form::textarea('description', null, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'description')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('summary', 'Summary', array('class' => 'control-label')) !!}
                                {!! Form::text('summary', null, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'summary')!!}
                                <span class="help-block">This is shown on checkout and payment gateway pages.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Roles To Grant To The User</label>
                            @foreach($roles as $roleid => $role)
                                <div class="checkbox">

                                        @if($role != 'Member')
                                        <label>
                                            {!! Form::checkbox('roles[]', $roleid, null, array('class' => 'styled')) !!} {!! $role !!}
                                        </label>
                                        @endif
                                </div>
                            @endforeach
                            {!!inputError($errors, 'roles')!!}
                            <span class="help-block">The Member role is included as default with every membership.</span>
                        </div>
                        <div class="col-md-6">
                            <label>Roles To Remove From The User</label>
                            @foreach($roles as $roleid => $role)
                                <div class="checkbox">
                                        @if($role != 'Member')
                                            <label>
                                            {!! Form::checkbox('roles_to_remove[]', $roleid, null, array('class' => 'styled')) !!} {!! $role !!}
                                            </label>
                                        @endif
                                </div>
                            @endforeach
                            {!!inputError($errors, 'roles_to_remove')!!}
                            <span class="help-block">Optional : Remove a role from a user.</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('type', 'Type of Membership', array('class' => 'control-label')) !!}
                                {!! Form::select('type', array('free' => 'Free', 'paid' => 'Paid'),null, array('class' => 'form-control', 'id' => 'type')) !!}
                                {!!inputError($errors, 'type')!!}
                            </div>
                        </div>
                    </div>

                    <div class="free_membership_inputs">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="register_default" value="1" id="register_default">
                                        Default Membership for the registration form
                                        {!!inputError($errors, 'register_default')!!}
                                        <span class="help-block">Choose if this is the membership used for the free registration form.</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('expires', 'Expires', array('class' => 'control-label')) !!}<br>

                                <label>
                                    <input type="checkbox" name="expires" value="1" id="expires">
                                    {!!inputError($errors, 'expires')!!}
                                    Does This membership expire
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="paid_membership_inputs2">

                        <div class="row paid_membership_inputs">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('amount', 'Membership Amount', array('class' => 'control-label')) !!}
                                    <div class="input-group">
                                        <span class="input-group-addon">{{Countries::siteCountry()->currency_symbol}}</span>
                                        {!! Form::text('amount', null, array('class' => 'form-control', 'placeholder' => '9.99', 'pattern' => '^\\$?(([1-9](\\d*|\\d{0,2}(,\\d{3})*))|0)(\\.\\d{1,2})?$')) !!}
                                    </div>
                                    {!!inputError($errors, 'amount')!!}
                                    <span class="help-block">Normal currency format rules apply, 9.99, 10,000, 10,000.00</span>
                                </div>
                            </div>
                        </div>






                        <div class="panel panel-default border-grey subscription-inputs">
                            <div class="panel-heading">
                                <h6 class="panel-title">Subscription Settings</h6>
                                <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                            <div class="panel-body">

                                <div class="row paid_membership_inputs">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('subscription', 'Payment Subscription', array('class' => 'control-label')) !!}<br>

                                            <label>
                                                <input type="checkbox" name="subscription" value="1" id="subscription">
                                                {!!inputError($errors, 'subscription')!!}
                                                Has Recurring Payment Subscription
                                            </label>
                                            <span class="help-block">Selecting this will make the payment reoccur. Otherwise will be a one off payment.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('subscription_period_amount', 'Subscription Period Amount', array('class' => 'control-label')) !!}
                                            {!! Form::text('subscription_period_amount', null, array('class' => 'form-control')) !!}
                                            {!!inputError($errors, 'subscription_period_amount')!!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('subscription_period_type', 'Subscription Period Type', array('class' => 'control-label')) !!}
                                            {!! Form::select('subscription_period_type', array('Days' => 'Days', 'Weeks' => 'Weeks', 'Months' => 'Months', 'Years' => 'Years'),null, array('class' => 'form-control')) !!}
                                            {!!inputError($errors, 'subscription_period_type')!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">{!! Form::label('expire', 'Expiration Rules', array('class' => 'control-label')) !!}<br></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" name="expired_remove_roles" value="1">
                                                {!!inputError($errors, 'expired_remove_roles')!!}
                                                Remove Granted Roles On Expiration ?
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" name="expired_change_status" value="1">
                                                {!!inputError($errors, 'expired_change_status')!!}
                                                Change User Status On Expiration ?
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('expired_change_status_to', 'Change Status To', array('class' => 'control-label')) !!}
                                            {!! Form::select('expired_change_status_to', array('active' => 'Active', 'inactive' => 'Inactive'),null, array('class' => 'form-control')) !!}
                                            {!!inputError($errors, 'expired_change_status_to')!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Grant Following Roles on Expiration</label>
                                        @foreach($roles as $roleid => $role)
                                            <div class="checkbox">
                                                    @if($role != 'Member')
                                                        <label>
                                                        {!! Form::checkbox('expired_add_roles[]', $roleid, null, array('class' => 'styled')) !!} {!! $role !!}
                                                        </label>
                                                    @endif

                                            </div>
                                        @endforeach
                                        {!!inputError($errors, 'roles')!!}
                                        <span class="help-block">The Member role is included as default with every membership.</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('expire_email_id', 'Email To Send After Membership Expires', array('class' => 'control-label')) !!}
                                            {!! Form::select('expire_email_id', $emails,null, array('class' => 'form-control')) !!}
                                            {!!inputError($errors, 'expire_email_id')!!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End paid membership -->


                    <div class="panel panel-default border-grey">
                        <div class="panel-heading">
                            <h6 class="panel-title">Redirection / Email Settings </h6>
                            <div class="heading-elements">
                            </div>
                            <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('page_after_registration', 'Page To Show User After Registration', array('class' => 'control-label')) !!}
                                        {!! Form::select('page_after_registration', $pages,null, array('class' => 'form-control')) !!}
                                        {!!inputError($errors, 'page_after_registration')!!}
                                    <span class="help-block">If a members page is selected then select Automatically login below.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="paid_membership_inputs">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('members_page_after_payment', 'Page To Show User After Payment (Upgrade)', array('class' => 'control-label')) !!}
                                            {!! Form::select('members_page_after_payment', $memberPages,null, array('class' => 'form-control')) !!}
                                            {!!inputError($errors, 'members_page_after_payment')!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('guest_page_after_payment', 'Page To Show Guest After Payment', array('class' => 'control-label')) !!}
                                            {!! Form::select('guest_page_after_payment', $pages,null, array('class' => 'form-control')) !!}
                                            {!!inputError($errors, 'guest_page_after_payment')!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('emails_id', 'Email To Send After Registration', array('class' => 'control-label')) !!}
                                        {!! Form::select('emails_id', $emails,null, array('class' => 'form-control')) !!}
                                        {!!inputError($errors, 'emails_id')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="email_validate" value="1">
                                            {!!inputError($errors, 'email_validate')!!}
                                            New members must validate email before login ?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="login_after_register" value="1">
                                            {!!inputError($errors, 'login_after_register')!!}
                                            Automatically login new user after registration ?
                                        </label>
                                        <span class="help-block">This will bypass the validate email (if selected) until they next login.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="panel panel-default border-grey">
                        <div class="panel-heading">
                            <h6 class="panel-title">Membership Visability / Status</h6>
                            <div class="heading-elements">
                            </div>
                            <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="allow_user_signups" value="1" id="allow_user_signups">
                                            Allow User Signups
                                            {!!inputError($errors, 'allow_user_signups')!!}
                                            <span class="help-block">Allow users to register with this membership type. If unchecked only admins can manually assign this membership.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="display_in_collections" value="1" id="subscription">
                                            Display In Collections
                                            {!!inputError($errors, 'display_in_collections')!!}
                                            <span class="help-block">Display this membership where ever a public list of memberships is shown.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('position', 'Display Position', array('class' => 'control-label')) !!}
                                        {!! Form::select('position', $position,null, array('class' => 'form-control')) !!}
                                        {!!inputError($errors, 'position')!!}
                                        <span class="help-block">Position this membership is listed.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('status', 'Membership Status', array('class' => 'control-label')) !!}
                                        {!! Form::select('status', array('active' => 'Active', 'inactive' => 'Inactive'),null, array('class' => 'form-control')) !!}
                                        {!!inputError($errors, 'status')!!}
                                        <span class="help-block">Change to Inactive to disable this whole membership type.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Create The Membership<i class="far fa-save position-right"></i></button>
                        </div>
                    </div>

                </div>
            </div>


        </div>



    </div>
    {!! Form::close() !!}
@stop


