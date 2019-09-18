<?php

function isFirstFeed($i)
{
    if($i == 1) return 'feedColFirst';
}

function listPrice($price)
{
    if(starts_with($price, '£')) return $price;
    return '£'.$price;
}

function hasAFeed($vehicle, $feed)
{
    if($vehicle->make && $vehicle->make->id != 1)
    {
        return hasMakeFeed($vehicle->make->slug, $feed);
    }
    $search = getSearchWord($vehicle);
    return hasSearchFeed($search, $feed);
}

function hasSearchFeed($search, $feed)
{
    foreach ($feed as $item)
    {
        if($item->vehicleMake == NULL
            && $item->vehicleModel === NULL
            && $item->search === $search
            && $item->auctioneer === NULL
            && $item->location === NULL
        ) return true;
    }

    return false;
}

function hasMakeFeed($make, $feed)
{
    foreach ($feed as $item)
    {
        if($item->vehicleMake == $make
            && $item->vehicleModel === NULL
            && $item->search === NULL
            && $item->auctioneer === NULL
            && $item->location === NULL
        ) return true;
    }

    return false;
}

function hasModelFeed($model, $feed)
{
    foreach ($feed as $item)
    {
        if($item->vehicleMake === NULL
            && $item->vehicleModel == $model
            && $item->search === NULL
            && $item->auctioneer === NULL
            && $item->location === NULL
        ) return true;
    }
    return false;
}

function hasMakeModelFeed($make, $model, $feed)
{
    foreach ($feed as $item)
    {
        if($item->vehicleMake == $make
            && $item->vehicleModel == $model
            && $item->search === NULL
            && $item->auctioneer === NULL
            && $item->location === NULL
        ) return true;
    }
    return false;
}

function hasAuctioneerFeed($auctioneer, $feed)
{
    foreach ($feed as $item)
    {
        if($item->vehicleMake === NULL
            && $item->vehicleModel === NULL
            && $item->search === NULL
            && $item->auctioneer === $auctioneer
            && $item->location === NULL
        ) return true;
    }
    return false;
}

function getSearchWord($vehicle)
{
    $words = str_word_count($vehicle->name,1);
    if(isset($words[0])) return $words[0];
    return $vehicle->name;
}