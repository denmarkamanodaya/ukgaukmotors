<div class="form-group">
    {!! Form::label('title', 'Menu Title:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title', 'required')) !!}
        {!!inputError($errors, 'title')!!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('description', 'Description:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('description', null, array('class' => 'form-control', 'id' => 'description', 'required')) !!}
        {!!inputError($errors, 'description')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('role_id', 'Role Access:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::select('role_id', $roles, null, ['class' => 'form-control']) !!}
        {!!inputError($errors, 'role_id')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('parent_start', 'Parent Start Html:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('parent_start', null,['class' => 'form-control', 'id' => 'parent_start', 'rows' => '3']) !!}
        {!!inputError($errors, 'parent_start')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('parent_end', 'Parent End Html:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('parent_end', null,['class' => 'form-control', 'id' => 'parent_end', 'rows' => '3']) !!}
        {!!inputError($errors, 'parent_end')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('child_start', 'Child Start Html:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('child_start', null,['class' => 'form-control', 'id' => 'child_start', 'rows' => '3']) !!}
        {!!inputError($errors, 'child_start')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('child_end', 'Child End Html:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('child_end', null,['class' => 'form-control', 'id' => 'child_end', 'rows' => '3']) !!}
        {!!inputError($errors, 'child_end')!!}
    </div>
</div>

