{!! Form::hidden('menu_id', $menu->id) !!}
<div class="form-group">
    {!! Form::label('title', 'Item Type:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::select('type', array('normal' => 'Normal', 'dropdown' => 'Dropdown', 'dropdown-header' => 'Dropdown Header', 'dropdown-submenu' => 'Dropdown Submenu'), null, array('class' => 'form-control', 'id' => 'description')) !!}
        {!!inputError($errors, 'type')!!}
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
    {!! Form::label('url', 'URL:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('url', null, array('class' => 'form-control', 'id' => 'url')) !!}
        {!!inputError($errors, 'url')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('title', 'Title:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title', 'required')) !!}
        {!!inputError($errors, 'title')!!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('permission', 'Permission:', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::select('permission', $permissionList, null, ['class' => 'form-control']) !!}
        {!!inputError($errors, 'permission')!!}
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