@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/newsletter.js')}}"></script>
    <script src="{{url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>$('#lfm').filemanager('image', {prefix: '/filemanager'});</script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletter' => '/admin/newsletter', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Edit Newsletter
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Newsletter Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($curnewsletter, array('method' => 'POST', 'url' => '/admin/newsletter/'.$curnewsletter->slug.'/edit')) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('newsletter_templates_id', 'Newsletter Theme', array('class' => 'control-label')) !!}
                                {!! Form::select('newsletter_templates_id', $themes, null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('newsletter_templates_id'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('newsletter_templates_id') }}</span>
                                @endif
                                <span class="help-block">Choose the main email theme (used for welcome, responders etc).</span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('shot_template_id', 'Mail Shot Theme', array('class' => 'control-label')) !!}
                                {!! Form::select('shot_template_id', $themes, null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('shot_template_id'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('shot_template_id') }}</span>
                                @endif
                                <span class="help-block">Choose the mail shot theme.</span>

                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Newsletter Title', array('class' => 'control-label')) !!}
                                {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('title'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('title') }}</span>
                                @endif
                                <span class="help-block">Name your campaign, Note: Title will be visible to public.</span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('summary', 'Newsletter Summary', array('class' => 'control-label')) !!}
                                {!! Form::textarea('summary', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('summary'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('summary') }}</span>
                                @endif
                                <span class="help-block">Provide a short summary about this newsletter.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('description', 'Newsletter Description', array('class' => 'control-label')) !!}
                                {!! Form::textarea('description', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('description'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('description') }}</span>
                                @endif
                                <span class="help-block">Provide a full description for your subscribers.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('confirm_non_member', '1', null, array('class' => 'styled')) !!} Do you want Non Members to double opt in ?
                                    </label>
                                </div>
                                @if ($errors->has('confirm_non_member'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('confirm_non_member') }}</span>
                                @endif
                                <span class="help-block">This will send non members a confirmation email.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('visible_in_lists', '1', null, array('class' => 'styled')) !!} Do you want this newsletter to show up in any list
                                    </label>
                                </div>
                                @if ($errors->has('visible_in_lists'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('visible_in_lists') }}</span>
                                @endif
                                <span class="help-block">Any Descriptive list of newsletters show on the site will contain this campaign.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('allow_subscribers', '1', null, array('class' => 'styled')) !!} Do you want this as an active newsletter
                                    </label>
                                </div>
                                @if ($errors->has('allow_subscribers'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('allow_subscribers') }}</span>
                                @endif
                                <span class="help-block">Allow signups to this campaign otherwise subscribers can only be added by an admin.</span>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Auto Join</h6>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('autojoin_register', '1', null, array('class' => 'styled')) !!} Subscribe New Members Automatically
                                    </label>
                                </div>
                                @if ($errors->has('autojoin_register'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('autojoin_register') }}</span>
                                @endif
                                <span class="help-block">Any member that joins the site is signed up to this newsletter.</span>
                            </div>
                        </div>
                    </div>
                    @if(count(config('main.multisite_sites')) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Sites</label>
                                    @foreach(config('main.multisite_sites') as $site)
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('multisite_sites[]', $site, null, array('class' => 'styled')) !!} {!! $site !!}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if ($errors->has('multisite_sites'))
                                        <span class="help-block validation-error-label" for="name">{{ $errors->first('multisite_sites') }}</span>
                                    @endif
                                    <span class="help-block">Select which multisite sites to allow autojoin.</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('autojoin_send_welcome_email', '1', null, array('class' => 'styled')) !!} Send Welcome Email
                                    </label>
                                </div>
                                @if ($errors->has('autojoin_send_welcome_email'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('autojoin_send_welcome_email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('autojoin_start_responder', '1', null, array('class' => 'styled')) !!} Start Responder Sequence
                                    </label>
                                </div>
                                @if ($errors->has('autojoin_start_responder'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('autojoin_start_responder') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Roles</label>
                                @foreach($roles as $name => $role)
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('roles[]', $name, null, array('class' => 'styled')) !!} {!! $role !!}
                                        </label>
                                    </div>
                                @endforeach
                                @if ($errors->has('roles'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('roles') }}</span>
                                @endif
                                <span class="help-block">Select which roles can see and subscribe to this campaign.</span>
                            </div>
                        </div>
                    </div>
                    @if($newsletters)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Newsletter Restriction</label>
                                    @foreach($newsletters as $id => $newsletter)
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('newsletter_no_join[]', $id, isNoJoin($id, $curnewsletter->noJoin), array('class' => 'styled')) !!} {!! $newsletter !!}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if ($errors->has('newsletter_no_join'))
                                        <span class="help-block validation-error-label" for="name">{{ $errors->first('newsletter_no_join') }}</span>
                                    @endif
                                    <span class="help-block">Do not subscribe if they already belong to above campaigns.</span>
                                </div>
                            </div>
                        </div>



                    <div class="row">
                        <div class="col-md-12">

                            <h5>Role Change Rules</h5>
                            <p>Here you select what happens when a user changes role. ie if a user is a guest and then registers you don't want them to be in the guest only newsletter, so move them to the members newsletter.<br>You can also choose to start or not any autoresponder message sequence attached to the campaign.</p>

                            <table id="role_change" class="table table-striped table-bordered table-hover invoice_items">
                                <thead>
                                <tr>
                                    <th class="center">Role Action</th>
                                    <th>Role</th>
                                    <th>Move To Newsletter</th>
                                    <th>Start Responder</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0; ?>

                                <tr class="repeat no-display" id="{!! $i !!}-repeatable">

                                    <td>
                                        <div class="form-group">
                                            {!! Form::select("role_action[$i]", ['gain' => 'Gain', 'lose' => 'Lose'],null, array('class' => 'form-control mt-15')) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::select("role_id[$i]", $roles,null, array('class' => 'form-control mt-15')) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::select("newsletter_new_id[$i]", $newsletters,null, array('class' => 'form-control mt-15')) !!}
                                        </div>
                                    </td>

                                    <td class="center">
                                        <div class="form-group center">
                                            {!! Form::checkbox("start_responder[$i]", '1',null, array('class' => 'mt-15 styled')) !!}
                                        </div>
                                    </td>

                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <a id="{!! $i !!}-repeatableid" data-original-title="Remove" data-placement="top" class="btn btn-bricky tooltips repeatable-del" href="#"><i class="far fa-times"></i> Remove</a>

                                        </div>
                                    </td>
                                </tr>

                                @foreach($curnewsletter->roleMove as $roleMove)
                                    <?php $i++; ?>
                                    <tr class="repeat" id="{!! $i !!}-repeatable">

                                        <td>
                                            <div class="form-group">
                                                {!! Form::select("role_action[$i]", ['gain' => 'Gain', 'lose' => 'Lose'],$roleMove->role_action, array('class' => 'form-control mt-15')) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                {!! Form::select("role_id[$i]", $roles,$roleMove->role_id, array('class' => 'form-control mt-15')) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                {!! Form::select("newsletter_new_id[$i]", $newsletters,$roleMove->newsletter_new_id, array('class' => 'form-control mt-15')) !!}
                                            </div>
                                        </td>

                                        <td class="center">
                                            <div class="form-group center">
                                                {!! Form::checkbox("start_responder[$i]", '1',$roleMove->start_responder, array('class' => 'mt-15 styled')) !!}
                                            </div>
                                        </td>

                                        <td class="center">
                                            <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                <a id="{!! $i !!}-repeatableid" data-original-title="Remove" data-placement="top" class="btn btn-bricky tooltips repeatable-del" href="#"><i class="far fa-times"></i> Remove</a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                            <span id="add-new-item" data-original-title="Add" data-placement="top" class="btn btn-info repeatable-add mt-15 mb-10"><i class="far fa-list"></i>  Add New Role Change Rule</span>

                        </div>
                    </div>

                    @endif


                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5> Email Messages</h5>
                            <br><button class="btn btn-primary btn-rounded btn-xs" data-target="#helptext_emails" data-toggle="modal" type="button">
                                Replacement Fields
                                <i class="far fa-question position-right"></i>
                            </button><br><br>

                            <div class="form-group">
                                {!! Form::label('email_from', 'From Email Address', array('class' => 'control-label')) !!}
                                {!! Form::text('email_from', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('email_from'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('email_from') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                {!! Form::label('email_from_name', 'From Name', array('class' => 'control-label')) !!}
                                {!! Form::text('email_from_name', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('email_from_name'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('email_from_name') }}</span>
                                @endif
                            </div>

                        @foreach($curnewsletter->mails as $email)
                                @if($email->message_type == 'welcome_email')
                                    <div class="form-group">
                                        {!! Form::label('welcome_email_subject', 'Welcome Email Subject', array('class' => 'control-label')) !!}
                                        {!! Form::text('welcome_email_subject', $email->subject, array('class' => 'form-control', 'required')) !!}
                                        @if ($errors->has('welcome_email_subject'))
                                            <span class="help-block validation-error-label" for="title">{{ $errors->first('welcome_email_subject') }}</span>
                                        @endif

                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('welcome_email', 'Welcome Email', array('class' => 'control-label')) !!}
                                        {!! Form::textarea('welcome_email', $email->html_message, array('class' => 'form-control')) !!}
                                        @if ($errors->has('welcome_email'))
                                            <span class="help-block validation-error-label" for="title">{{ $errors->first('welcome_email') }}</span>
                                        @endif
                                        <span class="help-block">Your initial email sent to new subscribers.</span>
                                    </div>
                                @endif

                                    @if($email->message_type == 'confirmation_email')
                                        <div class="form-group">
                                            {!! Form::label('confirmation_email_subject', 'Confirmation Email Subject', array('class' => 'control-label')) !!}
                                            {!! Form::text('confirmation_email_subject', $email->subject, array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('confirmation_email_subject'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('confirmation_email_subject') }}</span>
                                            @endif

                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('confirmation_email', 'Confirmation Email', array('class' => 'control-label')) !!}
                                            {!! Form::textarea('confirmation_email', $email->html_message, array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('confirmation_email'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('confirmation_email') }}</span>
                                            @endif
                                            <span class="help-block">Opt in confirmation email.</span>
                                        </div>
                                    @endif

                                    @if($email->message_type == 'unsubscribe_email')
                                        <hr>
                                        <div class="form-group">
                                            {!! Form::label('unsubscribe_email', 'Unsubscribe Email Text', array('class' => 'control-label')) !!}
                                            {!! Form::textarea('unsubscribe_email', $email->html_message, array('class' => 'form-control')) !!}
                                            @if ($errors->has('unsubscribe_email'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('unsubscribe_email') }}</span>
                                            @endif
                                            <span class="help-block">The Unsubscribe text to add to each outgoing email.</span>
                                        </div>
                                    @endif

                            @endforeach

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5> Newsletter Pages</h5>
                            <br>{!! Shortcode::showButton() !!}<br><br>



                            @foreach($curnewsletter->pages as $page)
                                @if($page->page_type == 'subscribed_page')
                                    <div class="form-group">
                                        {!! Form::label('subscribed_page', 'Subscribe Success Page', array('class' => 'control-label')) !!}
                                        {!! Form::textarea('subscribed_page', $page->content, array('class' => 'form-control', 'required')) !!}
                                        @if ($errors->has('subscribed_page'))
                                            <span class="help-block validation-error-label" for="title">{{ $errors->first('subscribed_page') }}</span>
                                        @endif
                                        <span class="help-block">Page to show after successful subscription.</span>
                                    </div>
                                @endif

                                @if($page->page_type == 'unsubscribed_page')
                                        <div class="form-group">
                                            {!! Form::label('unsubscribed_page', 'Unsubscribed Page', array('class' => 'control-label')) !!}
                                            {!! Form::textarea('unsubscribed_page', $page->content, array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('unsubscribed_page'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('unsubscribed_page') }}</span>
                                            @endif
                                            <span class="help-block">Page to show after unsubscription.</span>
                                        </div>
                                @endif

                                    @if($page->page_type == 'confirmed_email')
                                        <div class="form-group">
                                            {!! Form::label('confirmed_email', 'Confirmed Email', array('class' => 'control-label')) !!}
                                            {!! Form::textarea('confirmed_email', $page->content, array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('confirmed_email'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('confirmed_email') }}</span>
                                            @endif
                                            <span class="help-block">Page to show after subscriber has confirmed their email address.</span>
                                        </div>
                                    @endif

                            @endforeach



                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('featured_image', 'Featured Image', array('class' => 'control-label')) !!}

                                <div class="input-group">
                            <span class="input-group-btn">
                                <a id="lfm" data-input="featured_image" data-preview="featured_image_preview_wrap" class="btn btn-primary">
                                <i class="far fa-image"></i> Choose
                                </a>
                            </span>
                                    {!! Form::text('featured_image', null, array('class' => 'form-control', 'id' => 'featured_image')) !!}

                                </div>

                                <div class="row mt-10" id="featured_image_preview_wrap">
                                    <div class="col-md-6 text-center"><img id="thumbnail_featured_image" class="" style="max-height:100px;"></div>
                                    <div class="col-md-6 text-center"><button type="button" class="btn btn-warning" id="featured_image_remove">Remove Image <i class="far fa-times position-right"></i></button></div>

                                </div>
                                @if ($errors->has('featured_image'))
                                    <span class="help-block" for="featured_image">{{ $errors->first('featured_image') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary">Update Newsletter Campaign<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}
    @include('newsletter::admin/email_help')
    {!! Shortcode::showModal() !!}

@stop


