<?php

function postLink($post, $area='', $category='blog')
{
    $pl_id = \Settings::get('blog_link_structure');
    $date = '';

    switch($pl_id)
    {
        case 1:
            $postFormat = "Y/m/d/title";
            break;
        case 2:
            $postFormat = "Y/m/title";
            break;
        case 3:
            $postFormat = "Y/title";
            break;
        case 4:
            $postFormat = "title";
            break;
        default:
            $postFormat = "Y/m/d/title";
    }

    $Pf_parts = explode('/', $postFormat);

    $dateformat = '';
    foreach($Pf_parts as $key => $part)
    {

            if (strtolower($part) == 'y') {
                $dateformat .= 'Y/';
            }
            if (strtolower($part) == 'm') {
                $dateformat .= 'n/';
            }
            if (strtolower($part) == 'd') {
                $dateformat .= 'j/';
            }

    }
    $dateformat = rtrim($dateformat, '/');
    if($dateformat != '') $date = $post->publish_on->format($dateformat);
    if($area != '') $area = $area.'/';
    if($date != '') $date = $date.'/';

    if($category == 'blog') $category = 'post/';
    if($category == 'snippet') $category = 'snippet/';

    return(url($area.$category.$date.$post->slug));
}

function tagInputFormat($tags)
{
    $output = '';
    foreach ($tags AS $tag)
    {
        $output .= $tag->name.',';
    }

    return rtrim($output, ',');
}