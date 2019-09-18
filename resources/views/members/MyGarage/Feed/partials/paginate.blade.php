@if ($paginator->lastPage() > 1)
    @if($paginator->currentPage() != $paginator->lastPage())
        <div class="loadMoreWrap"><a href="{{ $paginator->appends(['feed' => $feed->id])->url($paginator->currentPage()+1) }}" class="loadMore btn-contact cs-color csborder-color"><i class="fas fa-cogs"></i> Load More</a></div>
    @endif

@endif