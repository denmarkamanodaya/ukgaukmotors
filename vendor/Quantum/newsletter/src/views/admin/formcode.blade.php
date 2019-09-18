<script type="text/javascript" src="{{url('assets/js/newsletterSubscribe.js')}}"></script>
<div id="newsletter_subscribe_form">
    <form method="POST" action="{!! url('/newsletter/subscribe/'.$newsletter->slug) !!}" accept-charset="UTF-8" id="newsletter_subscribe">

        <div class="row" id="subscribe_error_wrap" style="display:none;">
            <div class="col-md-12" id="subscribe_errors">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" id="first_name_group">
                <div class="form-group">
                    <label for="first_name" class="control-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="" id="first_name">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="last_name_group">
                    <label for="last_name" class="control-label">Last name</label>
                    <input type="text" class="form-control" name="last_name" value="" id="last_name">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="email_group">
                    <label for="email" class="control-label">Email Address</label>
                    <input class="form-control" required="required" name="email" type="email" value="" id="email">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Subscribe<i class="far fa-envelope position-right"></i></button>
            </div>

        </div>

    </form>
</div>