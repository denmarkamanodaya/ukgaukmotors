<?php
/**
 * Set Dealer image path
 * @param null $auctioneerId
 * @return bool|string
 */
function dealer_logo_path($dealerId = null)
{
    if($dealerId)
    {
        return config('main.public_path') . '/images/dealers/'. $dealerId . '/';
    }
    return false;
}

/**
* Dealer logo preview
* @param $auctioneer
* @return string
    */
function dealer_logo_preview($dealer)
{
    $preview = '<a class="previewImage" onclick="return false;" href="'.url("/images/dealers/".$dealer->id."/".$dealer->logo).'">
    <img border="0" height="15" src="'.config('app.url').'/images/insert-image.png" alt=""></a>';
    return $preview;
}

function logo_previewAuc2($dealer)
{
    $preview = '<a class="previewImage" onclick="return false;" href="'.env('SITE_URL').'/images/dealers/'.$dealer->id.'/'.$dealer->logo.'">
    <img border="0" height="15" src="'.env('SITE_URL').'/images/dealers/'.$dealer->id.'/'.$dealer->logo.'" alt=""></a>';
    return $preview;
}

function dealerLogoUrl($dealer, $thumb=null)
{
    if($thumb)
    {
        return url('/images/dealers/'.$dealer->id.'/thumb'.$thumb.'-'.$dealer->logo);

    }
    return url('/images/dealers/'.$dealer->id.'/'.$dealer->logo);
}