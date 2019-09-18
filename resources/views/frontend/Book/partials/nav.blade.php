<div class="row mt-50">
    <div class="col-md-12 text-center">

        @if($links['previous'] != '')
         <a href="{!! url($links['previous']) !!}"><< Previous Page</a>
        @else
            <a href="{!! url('/book/'.$book->slug) !!}"><< Contents</a>
        @endif
            @if($links['next'] != '')
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{!! url($links['next']) !!}">Next Page >></a>
            @endif

    </div>
</div>