@foreach(array_chunk($permissions->toarray(), 1) as $area)
    <div class="col-lg-6">
        <div class="content-group-sm">
            <h2 class="no-margin text-semibold">{{$area[0]['title']}}</h2>
        </div>
        @foreach($area[0]['permissiongroups'] as $group)

            <div class="content-group-sm">
                <h5 class="no-margin text-semibold">{{$group['title']}}</h5>
            </div>

            @foreach($group['permissions'] as $perm)
                <div class="content-group-sm">
                    <div class="checkbox">
                        <label>
                            <div class=""><span class="">{!! Form::checkbox('permissions[]', $perm['id'], array_pluck($role->permissions, 'id'), array('class' => 'styled', 'multiple')) !!}</span></div>
                            {{$perm['title']}}
                        </label>
                    </div>
                </div>
            @endforeach

        @endforeach
    </div>
@endforeach



