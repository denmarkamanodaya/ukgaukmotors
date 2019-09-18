
    @foreach($helptext as $help)
        <!-- Primary modal -->
        <div id="shortcodes_info" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">{!! $help->title !!}</h6>
                    </div>

                    <div class="modal-body">
                        {!! $help->content !!}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /primary modal -->
    @endforeach