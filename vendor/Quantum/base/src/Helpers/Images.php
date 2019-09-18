<?php

/**
 * Set user photo path
 * @param null $username
 * @return string
 */
function user_photo_path($username = null)
{
    if($username)
    {
        return config('main.public_path') . '/images/user/'. $username . '/';
    }
    return config('main.public_path') . '/images/user/'. Auth::user()->username . '/';
}

function user_photo_MultiSiteUrl($username = null)
{
    if($username)
    {
        return config('main.multisiteUrl') . '/images/user/'. $username . '/';
    }
    return config('main.multisiteUrl') . '/images/user/'. Auth::user()->username . '/';
}

/**
 * Set user photo url
 * @param null $username
 * @return string
 */
function user_photo_url($user=null)
{
    if($user)
    {
        return config('app.url') . '/images/user/'. $user->username . '/';
    }
    return config('app.url') . '/images/user/'. Auth::user()->username . '/';
}

/**
 * Show a profile picture
 * @param $pic
 * @param $size
 * @param null $class
 * @param null $username
 * @return bool|string
 */
function show_profile_pic($user, $class=null)
{
    if($user->profile->picture != '')
    {
        if(starts_with($user->profile->picture, 'http')) return '<img src="'. str_replace('http', 'https', $user->profile->picture) .'" class="'.$class.'">';
        return '<img src="' .config('app.url') . '/images/user/'.$user->username.'/'.$user->profile->picture.'" class="'.$class.'">';
    }

    return showGravatar($user->email);
}

function showGravatar($email)
{
    if($email == '') return false;
    if(Settings::get('use_gravatar') == 'yes')
    {
        if($email)
        {
            $hash = md5(strtolower(trim($email)));
            return '<img src="http://www.gravatar.com/avatar/'.$hash.'" class="">';
        }
    }
    return false;
}

/**
 * Set Auctioneer image path
 * @param null $auctioneerId
 * @return bool|string
 */
function auctioneer_logo_path($auctioneerId = null)
{
    if($auctioneerId)
    {
        return config('main.public_path') . '/images/auctioneer/'. $auctioneerId . '/';
    }
    return false;
}

/**
 * Auction logo preview
 * @param $auctioneer
 * @return string
 */
function logo_preview($auctioneer)
{
    $preview = '<a class="previewImage" onclick="return false;" href="'.config('app.url').'/images/auctioneer/'.$auctioneer->id.'/'.$auctioneer->logo.'">
    <img border="0" height="15" src="'.config('app.url').'/images/insert-image.png" alt=""></a>';
    return $preview;
}

/**
 * Get small lot image
 * @param $lotId
 * @param $images
 * @return string
 */
function showSmallLotImages($lotId, $images)
{
    $images = unserialize($images);
    $imgurl = lotImageUrl($lotId);
    $output = '';
    foreach($images as $image)
    {
        $output .= '<img src="'.$imgurl.'/small_'.$image.'">';
    }
    return $output;
}

/**
 * Set the lot image url
 * @param $lotID
 * @return string
 */
function lotImageUrl($lotID)
{
    return config('app.url') . '/images/lots/'.$lotID;
}
