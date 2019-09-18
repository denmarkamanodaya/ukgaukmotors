<?php

function makeSummary($content)
{
    $summary = strip_tags($content);
    $summary = str_limit($summary, 300);
    return $summary;
}

function featuredImage($image)
{
    if(starts_with($image, 'http://')) return $image;
    if(starts_with($image, '/filemanager')) return url($image);
    $image = url('/photos/'.$image);
    return $image;
}

function activeLetter($letter, $current)
{
    if($letter == $current) return 'activeLetter';
    return '';
}

function hasIcon($item)
{
    if(isset($item->icon) && $item->icon != '') return '<i class="'.$item->icon.'"></i>';
}

function hasFeature($feature, $vehicleFeatures)
{
    foreach ($vehicleFeatures as $vFeature)
    {
        if($vFeature->id == $feature) return true;
    }
    return false;
}

function getVehicleImage($media, $vehicle, $area='admin')
{
    $pubpath = config('main.public_path').'/images/vehicle/'.$vehicle->id.'/'.$media->name;
    $puburl = config('app.url').'/images/vehicle/'.$vehicle->id.'/'.$media->name;
    if(File::exists($pubpath))
    {


        if($media->default_image == 0)
        {
            $image = '<a target="_blank" href="'.$puburl.'"><img src="'.$puburl.'" alt=""></a>';
            $image .= '<a href="'.url('/admin/vehicle/'.$vehicle->slug.'/classifiedEdit/defaultImage/'.$media->id).'"><button type="submit" class="btn btn-info mediaButton"><i class="far fa-check"></i> Set As Default</button></a>';

        } else {
            $image = '<div class="col-md-12 text-center defaultImageHeading">Default</div><a target="_blank" href="'.$puburl.'"><img src="'.$puburl.'" alt=""></a>';

        }
        $image .= '<a href="'.url('/admin/vehicle/'.$vehicle->slug.'/classifiedEdit/deleteImage/'.$media->id).'"><button type="submit" class="btn btn-danger mediaButton"><i class="far fa-times"></i> Delete</button></a>';

        return $image;
    }
    return false;
}

function showVehicleImage($media, $formatted=false, $exists=false, $thumb='')
{
    if(!$media) return '';
    //current/old location
    if($media->remote_type == 1)
    {
        $puburl = config('app.url').'/images/vehicle/'.$media->vehicle_id.'/'.$thumb.$media->name;
    }
    //new import location
    if($media->remote_type == 2)
    {
        $puburl = config('gauk.image_url').'/images/lots/'.$media->remote_lot.'/'.$thumb.$media->name;
    }
    //local moved to new location
    if($media->remote_type == 3)
    {
        $puburl = config('gauk.image_url').'/images/vehicles/'.$media->vehicle_id.'/'.$thumb.$media->name;
    }
    if(!isset($puburl)) return noVehicleImage($formatted);

    if($exists && $media->remote_type == 1)
    {
        if(!doesVehicleImageExist($media)) return noVehicleImage($formatted);
    }

    if($formatted) return '<img src="'.$puburl.'" alt="" class="img-responsive">';
    return $puburl;
}

function doesVehicleImageExist($media)
{
    //current/old location
    if($media->remote_type == 1)
    {
        $pubpath = config('main.public_path').'/images/vehicle/'.$media->vehicle_id.'/'.$media->name;
    }
    //new import location
    if($media->remote_type == 2)
    {
        return true;
        $pubpath = config('gauk.image_path').'/images/lots/'.$media->remote_lot.'/'.$media->name;
    }
    //local moved to new location
    if($media->remote_type == 3)
    {
        return true;
        $pubpath = config('gauk.image_path').'/images/vehicles/'.$media->vehicle_id.'/'.$media->name;
    }
    if(!isset($pubpath)) return false;
    if(File::exists($pubpath)) return true;
    return false;
}

function noVehicleImage($formatted=false)
{
    if($formatted) return '<img src="'.config('main.public_path').'/images/image-Not-available.jpg" alt="" class="img-responsive">';
    return config('main.public_path').'/images/image-Not-available.jpg';
}

function carEngineSizeArray()
{
    return $engineArray =[
        "1.0" => '1.0L',
        "1.2" => '1.2L',
        "1.3" => '1.3L',
        "1.4" => '1.4L',
        "1.5" => '1.5L',
        "1.6" => '1.6L',
        "1.7" => '1.7L',
        "1.8" => '1.8L',
        "1.9" => '1.9L',
        "2.0" => '2.0L',
        "2.1" => '2.1L',
        "2.2" => '2.2L',
        "2.3" => '2.3L',
        "2.4" => '2.4L',
        "2.5" => '2.5L',
        "2.6" => '2.6L',
        "2.7" => '2.7L',
        "2.8" => '2.8L',
        "2.9" => '2.9L',
        "3.0" => '3.0L',
        "3.1" => '3.1L',
        "3.2" => '3.2L',
        "3.3" => '3.3L',
        "3.4" => '3.4L',
        "3.5" => '3.5L',
        "3.6" => '3.6L',
        "3.7" => '3.7L',
        "3.8" => '3.8L',
        "3.9" => '3.9L',
        "4.0" => '4.0L',
        "4.1" => '4.1L',
        "4.2" => '4.2L',
        "4.3" => '4.3L',
        "4.4" => '4.4L',
        "4.5" => '4.5L',
        "4.6" => '4.6L',
        "4.7" => '4.7L',
        "4.8" => '4.8L',
        "4.9" => '4.9L',
        "5.0" => '5.0L',
        "5.1" => '5.1L',
        "5.2" => '5.2L',
        "5.3" => '5.3L',
        "5.4" => '5.4L',
        "5.5" => '5.5L',
        "5.6" => '5.6L',
        "5.7" => '5.7L',
        "5.8" => '5.8L',
        "5.9" => '5.9L',
        "6.0" => '6.0L',
        "6.1" => '6.1L',
        "6.2" => '6.2L',
        "6.3" => '6.3L',
        "6.4" => '6.4L',
        "6.5" => '6.5L',
        "6.6" => '6.6L',
        "6.7" => '6.7L',
        "6.8" => '6.8L',
        "6.9" => '6.9L',
        "7.0" => '7.0L',
        "7.1" => '7.1L',
        "7.2" => '7.2L',
        "7.3" => '7.3L',
        "7.4" => '7.4L',
        "7.5" => '7.5L',
        "7.6" => '7.6L',
        "7.7" => '7.7L',
        "7.8" => '7.8L',
        "7.9" => '7.9L',
        "8.0" => '8.0L',
        'unlisted' => 'Unlisted'
        ];
}

function bikeEngineSizeArray()
{
    return $engineArray =[
        "50" => '50cc',
        "125" => '125cc',
        "200" => '200cc',
        "300" => '300cc',
        "400" => '400cc',
        "500" => '500cc',
        "600" => '600cc',
        "700" => '700cc',
        "800" => '800cc',
        "900" => '900cc',
        "1000" => '1000cc',
        "1100" => '1100cc',
        "1200" => '1200cc',
        "1300" => '1300cc',
        "1400" => '1400cc',
        "1600" => '1600cc',
        "1800" => '1800cc',
        "2000" => '2000cc',
        'unlisted' => 'Unlisted'
    ];
}

function carBodyTypeArray()
{
   return $bodytype = [
       'convertible' => 'Convertible',
       'coupe' => 'Coupe',
       'estate' => 'Estate',
       'hatchback' => 'Hatchback',
       'mpv' => 'MPV',
       'saloon'=> 'Saloon',
       'suv' => 'SUV',
       'unlisted' => 'Unlisted'
    ];
}
function vanBodyTypeArray()
{
    return $bodytype = [
        'van'=> 'Van',
        'box_van'=> 'Box Van',
        'combi_van'=> 'Combi Van',
        'dropside' => 'Dropside',
        'luton' => 'Luton',
        'minibus' => 'Minibus',
        'panel_van' => 'Panel Van',
        'pickup' => 'Pickup',
        'unlisted' => 'Unlisted'
    ];
}
function bikeBodyTypeArray()
{
    return $bodytype = [
        'bike'=> 'Bike',
        'classic' => 'Classic',
        'commuter' => 'Commuter',
        'cruiser' => 'Cruiser',
        'minibike' => 'Minibike',
        'moped' => 'Moped',
        'motocross' => 'Motocross',
        'atv' => 'ATV',
        'roadster' => 'Roadster',
        'sports' => 'Sports',
        'sidecar' => 'Sidecar',
        'super_moto' => 'Super Moto',
        'three_wheeler' => 'Three Wheeler',
        'tourer'=> 'Tourer',
        'trail_bike' => 'Trail Bike',
        'unlisted' => 'Unlisted'
    ];
}

function serviceHistoryArray()
{
    return [
        'full'=> 'Full',
        'part' => 'Part',
        'none' => 'None',
        'unknown' => 'Unknown'
    ];
}

function priceToFloat($s)
{
    // convert "," to "."
    $s = str_replace(',', '.', $s);

    // remove everything except numbers and dot "."
    $s = preg_replace("/[^0-9\.]/", "", $s);

    // remove all seperators from first part and keep the end
    $s = str_replace('.', '',substr($s, 0, -3)) . substr($s, -3);

    // return float
    return (float) $s;
}