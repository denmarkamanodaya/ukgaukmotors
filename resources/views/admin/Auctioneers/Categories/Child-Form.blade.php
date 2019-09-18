<div class="form-group">
    {!! Form::label('child-name', 'Category Name:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('child_name', null, array('class' => 'form-control', 'id' => 'child_name')) !!}
        {!!inputError($errors, 'child_name')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('child_slug', 'Category Slug:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('child_slug', null, array('class' => 'form-control', 'id' => 'child_slug')) !!}
        {!!inputError($errors, 'child_slug')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('child_description', 'Description:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('child_description', null, array('class' => 'form-control', 'id' => 'child_description')) !!}
        {!!inputError($errors, 'child_description')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('child_icon', 'Icon:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('child_icon', null, ['class' => 'form-control icon-select iconPicker']) !!}
        {!!inputError($errors, 'child_icon')!!}
    </div>
</div>

<script>
    $('.iconPicker').fontIconPicker({
        theme: 'fip-bootstrap',
        source: fa_icons,
        useAttribute: false,
        emptyIconValue: 'none'
    });
</script>
