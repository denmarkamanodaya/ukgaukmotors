@if($vehicle->features->count() > 0)
<ul class="borderb mb-20">
    <div id="accessories" class="cs-auto-accessories detail-content">
        <div class="element-title" style="margin-bottom: 0!important;">
            <span>Vehicle Features</span>
        </div>
        @foreach($features as $featureTitle => $items)
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8 mb-10 cs-listing-icon">
                    <span class="mb-5">{!! hasIcon($items->first()->feature)!!}&nbsp;&nbsp;{{$featureTitle}}</span>
                    <ul class="">
                        @foreach($items as $item)
                            <li class="available" style="display:inline-block; width: 30%">
                                <span>{{$item->name}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</ul>

@endif