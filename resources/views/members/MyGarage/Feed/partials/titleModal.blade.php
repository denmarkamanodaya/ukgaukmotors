<div id="titleModal_{{$feed->id}}" class="modal fade" tabindex="-1" role="dialog">
    {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/setTitle')) !!}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Title</h4>
            </div>
            <div class="modal-body">
                {!! Form::hidden('id', $feed->id) !!}
                <p>To personalise this feed you can set a title by entering it below.</p>
                <div class="form-group">
                    <label for="title" class="control-label">Title:</label>
                    {!! Form::text('title', $feed->title, ['class' => '', 'autocomplete' => 'false']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Save Title" />
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    {!! Form::close() !!}
</div><!-- /.modal -->