<?php


function fontAwesomeList($return=true)
{
    $fa_css_prefix = 'fa';
    $cssLoc = public_path('assets/css/icons/font-awesome/css/font-awesome.css');

    $pattern = '/\.('.$fa_css_prefix.'-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
    $css = file_get_contents($cssLoc);

    preg_match_all($pattern, $css, $matches, PREG_SET_ORDER);

    $icons = array();
    $icons[0] = 'No Icon';
    foreach($matches as $match){
        $icons["fa ".$match[1]] = str_replace("\\", "&#x", $match[2])."; &nbsp;&nbsp;".$match[1];
    }
   // $icons2 = clipIconList();
   // $icons = array_merge($icons, $icons2);
    if($return)
    {
        return $icons;
    } else {
        echo $icons;
    }

}

function clipIconList($return=true)
{
    $clip_prefix = "clip";
    $cssLoc = asset('assets/fonts/style.css');
    $pattern = '/\.('.$clip_prefix.'-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
    $css = file_get_contents($cssLoc);
    preg_match_all($pattern, $css, $matches, PREG_SET_ORDER);
    $icons = array();

    foreach($matches as $match){
        $icons["".$match[1]] = str_replace("\\", "&#x", $match[2])."; ".$match[1];
    }
    if($return)
    {
        return $icons;
    } else {
        echo $icons;
    }
}