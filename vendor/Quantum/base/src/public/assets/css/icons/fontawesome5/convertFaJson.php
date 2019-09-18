<?php
/**
 * Created by PhpStorm.
 * User: dave
 * Date: 24/12/2017
 * Time: 10:07
 */

$icons=[];
$icons['regular'] = [];
$icons['solid'] = [];
$icons['light'] = [];
$icons['brands'] = [];

$fajson = file_get_contents('./icons.json');

$faicons = json_decode($fajson, true);

foreach ($faicons as $key => $icon)
{
    //loop styles
    foreach ($icon['styles'] as $style)
    {
        if(!isset($icons[$style])) $icons[$style] = [];
        $firstCharacter = substr($style, 0, 1);
        array_push($icons[$style], "fa".$firstCharacter.' fa-'.$key);
    }
}

$fp = fopen('sortedIcons.json', 'w+');
fwrite($fp, json_encode($icons));
//fwrite($fp, json_encode($icon_search));
fclose($fp);