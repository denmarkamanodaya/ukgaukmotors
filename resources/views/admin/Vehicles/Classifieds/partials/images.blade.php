@if (isset($vehicle->images) && $vehicle->images !== '')
    @php
        $images = explode(", ", $vehicle->images);
    @endphp
    @if (count($images))
        <div class="col-md-3 col-sm-6">
            <div class="thumbnail">
                <div class="thumb">
                    @foreach($images as $image)
                        <img src="{{ $image }}" alt="" class="img-responsive">
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@elseif (count($vehicle->media) > 0)
    @foreach($vehicle->media as $media)

        @if($media->type == 'image')
            <div class="col-md-3 col-sm-6">
                <div class="thumbnail">
                    <div class="thumb">
                        {!! showVehicleImage($media, true) !!}
                    </div>
                </div>
            </div>
        @endif

        @if($media->type == 'file')
            <div class="col-md-3 col-sm-6">
                <div class="thumbnail">
                    <div class="thumb">
                        {!! showVehicleImage($media, true) !!}
                    </div>
                </div>
            </div>
        @endif

    @endforeach
@endif