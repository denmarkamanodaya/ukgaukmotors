<div class="row mt-50">
    <div class="col-md-12 text-center">

        @if($links['previous'] != '')
         <a href="{!! url('/members'.$links['previous']) !!}"><< Previous Page</a>
        @else
            <a href="{!! url('/members/book/'.$book->slug) !!}"><< Contents</a>
        @endif
            @if($links['next'] != '')
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{!! url('/members'.$links['next']) !!}">Next Page >></a>
            @endif

    </div>
</div>