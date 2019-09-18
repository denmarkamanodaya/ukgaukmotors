<div class="form-group">
    {!! Form::label('name', 'Department Name:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!!inputError($errors, 'name')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('description', 'Description:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('description', null, array('class' => 'form-control', 'id' => 'description')) !!}
        {!!inputError($errors, 'description')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('icon', 'Icon:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('icon', null, ['class' => 'form-control icon-select iconPicker']) !!}
        {!!inputError($errors, 'icon')!!}
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <div class="checkbox">
        <label>
            {!! Form::checkbox('default', 1,null, array('id' => 'publishOnTime')) !!}
            Default Department for new tickets ?
        </label>
    </div>
    </div>

</div>