<div class="form-group">
    {!! Form::label('title', 'Role Name:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title')) !!}
        {!!inputError($errors, 'title')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', 'Role Slug:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!!inputError($errors, 'name')!!}
    </div>
</div>


