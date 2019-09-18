<div class="form-group">
    {!! Form::label('name', 'Category Name:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!!inputError($errors, 'name')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('slug', 'Category Slug:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('slug', null, array('class' => 'form-control', 'id' => 'slug')) !!}
        {!!inputError($errors, 'slug')!!}
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
        {!! Form::select('icon', $icons, null, ['class' => 'form-control icon-select']) !!}
        {!!inputError($errors, 'icon')!!}
    </div>
</div>


