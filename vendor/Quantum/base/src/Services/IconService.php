<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : IconService.php
 **/

namespace Quantum\base\Services;


class IconService
{

    public function makeJson()
    {
        $jsonPath = config('main.public_path').'/assets/css/icons/fontawesome5/icons.json';
        if(!\File::exists($jsonPath)) return false;

        $icons=[];
        $icons['regular'] = [];
        $icons['solid'] = [];
        $icons['light'] = [];
        $icons['brands'] = [];

        $fajson = file_get_contents($jsonPath);

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

        \Storage::put('public/sortedIcons.json', json_encode($icons));


    }

}