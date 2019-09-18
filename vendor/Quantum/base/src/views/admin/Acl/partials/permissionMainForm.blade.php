<div class="form-group">
    {!! Form::label('title', 'Permission Name:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title')) !!}
        {!!inputError($errors, 'title')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', 'Permission Slug:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!!inputError($errors, 'name')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('permission_group_id', 'Permission Group:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::select('permission_group_id', $permissionGroups, null, ['class' => 'form-control']) !!}
        {!!inputError($errors, 'permission_group_id')!!}
    </div>
</div>