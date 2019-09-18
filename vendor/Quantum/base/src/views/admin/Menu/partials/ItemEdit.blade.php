{!! Form::model($item,array('method' => 'POST', 'url' => '/admin/menu-item/'.$item->id.'/update', 'class' => 'form-horizontal')) !!}

@include('base::admin.Menu.partials.item')
{!! Form::hidden('id', $item->id) !!}
{!! Form::hidden('position', $item->position) !!}
<div class="form-group">
    <div class="col-lg-2"></div>
    <div class="col-lg-10">
        {!! Form::button('<i class="faf fa-save"></i> Edit Menu Item', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
    </div>
</div>

{!! Form::close() !!}

<div class="row>">
    <div class="col-md-12">
        <h5>Remove Menu Item</h5>
        {!! Form::model($item,array('method' => 'POST', 'url' => '/admin/menu-item/'.$item->id.'/delete', 'class' => 'form-horizontal')) !!}
        {!! Form::hidden('id', $item->id) !!}
        {!! Form::button('<i class="faf fa-times"></i> Delete Menu Item', array('type' => 'submit', 'class' => 'btn btn-warning')) !!}
        {!! Form::close() !!}
    </div>
</div>