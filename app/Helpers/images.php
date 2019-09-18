<?php

//old now use showVehicleImage()
function showVehicleImages($vehicleId, $media)
{

    if($media->remote_type == 1)
    {
        return url('/images/vehicle/'.$vehicleId.'/'.$media->name);
    }
    if($media->remote_lot && !is_null($media->remote_lot))
    {
        return 'http://mygauk.com/images/lots/'.$media->remote_lot.'/'.$media->name;
    }

    return false;

}

//old now use doesVehicleImageExist()
function vehicleImageExists($img, $id)
{
    if($img == '') return false;
    $public_path = config('main.public_path');
    if(\Illuminate\Support\Facades\File::exists($public_path.'/images/vehicle/'.$id.'/'.$img)) return true;
    return false;
}

function vehicleHeadScroller($vehicle)
{
    $output = '';
    if($vehicle->media->count() > 0)
       $i = -4;
    foreach ($vehicle->media as $media)
    {
        if(vehicleImageExists($vehicle->media->first()->name, $vehicle->id))
        {
            $output .= '<li class="slick-slide slick-cloned" data-slick-index="'.$i.'" aria-hidden="true" style="width: 475px;">
                        <div class="cs-media">
                            <figure><img alt="'.$vehicle->name.'" src="'.url('/images/vehicle/'.$vehicle->id.'/'.$vehicle->media->first()->name).'" data-echo="'.url('/images/vehicle/'.$vehicle->id.'/'.$vehicle->media->first()->name).'"></figure>
                        </div>
                    </li>';
           $i++; 
        }
    }
return $output;
}

/**
 * Set Auctioneer image path
 * @param null $auctioneerId
 * @return bool|string
 */
function vehicle_make_logo_path($vehicleMakeId = null)
{
    if($vehicleMakeId)
    {
        return config('main.public_path') . '/images/vehiclemake/'. $vehicleMakeId . '/';
    }
    return false;
}

function vehicle_make_logo_url($vehicleMakeId = null)
{
    if($vehicleMakeId)
    {
        return config('app.url') . '/images/vehiclemake/'. $vehicleMakeId . '/';
    }
    return false;
}

/**
 * Auction logo preview
 * @param $auctioneer
 * @return string
 */
function make_logo_preview($vehicleMake)
{
    $preview = '<a class="previewImage" onclick="return false;" href="'.config('app.url').'/images/vehiclemake/'.$vehicleMake->id.'/'.$vehicleMake->logo.'">
    <img border="0" height="15" src="'.config('app.url').'/images/insert-image.png" alt=""></a>';
    return $preview;
}

function show_make_logo($vehicleMake, $size=null, $class=null)
{
    if($vehicleMake->logo == '') return;
    if($size)
    {
        if(file_exists(vehicle_make_logo_path($vehicleMake->id).'thumb'.$size.'-'.$vehicleMake->logo))
        {
            return '<img src="'.vehicle_make_logo_url($vehicleMake->id).'thumb'.$size.'-'.$vehicleMake->logo.'" class="'.$class.'">';
        }
    }

    if(file_exists(vehicle_make_logo_path($vehicleMake->id).$vehicleMake->logo))
    {
        return '<img src="'.vehicle_make_logo_url($vehicleMake->id).$vehicleMake->logo.'" class="'.$class.'">';
    }

    return;
}

function featured_image($image)
{

    if (substr($image, 0, 4) === 'http')
    {
        return $image;
    }
    if (substr($image, 0, 12) === '/filemanager')
    {
        if(file_exists(config('main.public_path').$image))
        {
            return config('app.url').$image;
        }
        return false;
    }
    if(file_exists(config('main.public_path').'/photos/'.$image))
    {
        return config('app.url').'/photos/'.$image;
    }
    return false;
}

function isRecentVehicle($vehicle)
{
    $yesterday = Carbon\Carbon::now();
    $diff = $vehicle->created_at->diffInDays($yesterday);
    if ($diff == 0) {
        return 'recentVehicleTag';
    }
}

function priceAdjust($price)
{
    if(strpos('£', $price) == 0) return $price;
    return '£'.$price;
}