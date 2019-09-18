 <!-- Primary modal -->
    <div id="shortcode_help" class="modal fade">
        <div class="modal-dialog modal-dialog-shortcode">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Available Shortcodes</h6>
                </div>

                <div class="modal-body modal-body-shortcode">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($shortcodeHelp as $shortcodeSection => $shortcodes)
                            <li role="presentation" @if($shortcodeSection == 'User')class="active"@endif><a href="#short_{{ $shortcodeSection }}" aria-controls="short_{{ $shortcodeSection }}" role="tab" data-toggle="tab">{{ $shortcodeSection }}</a></li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach($shortcodeHelp as $shortcodeSection => $shortcodes)
                            <div role="tabpanel" @if($shortcodeSection == 'User')class="tab-pane active"@else class="tab-pane" @endif id="short_{{ $shortcodeSection }}">
                                @foreach($shortcodes as $shortcode)
                                    <div class="row">
                                        <div class="col-md-4">[{{$shortcode['name']}}]</div>
                                        <div class="col-md-8">{!! $shortcode['description']!!}</div>
                                        <div class="col-md-12"><hr></div>
                                        <hr>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /primary modal -->
