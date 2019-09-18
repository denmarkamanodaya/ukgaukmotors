@if($categories && $categories->count() > 0)
<a class="btn-gauk" data-toggle="collapse" href="#filters" role="button" aria-expanded="false" aria-controls="filters">Toggle filters</a>


        <div class="collapse multi-collapse" id="filters" style="text-align: left">
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
@endif