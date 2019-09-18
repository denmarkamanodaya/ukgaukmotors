@if($categories && $categories->count() > 0)
<a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Toggle filters</a>

<div class="row text-left">
    <div class="col-md-12">
        <div class="collapse multi-collapse" id="multiCollapseExample1">
            <div class="card card-body" id="filter-list">

                @foreach($categories as $category)
                    @foreach($category->children as $child)

                        @if($child->slug != 'uncategorised')
                                <span class="mr-10">{!! Form::checkbox('category[]', $child->id, null, array('class' => 'styled')) !!} {!! $child->name!!}</span>
                        @endif

                    @endforeach

                @endforeach
                <a href="#" id="resetFilters" class="ml-20">Reset Filters</a>
            </div>
        </div>
    </div>
</div>
@endif