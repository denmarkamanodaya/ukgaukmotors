<?php


function sortFileManagerList($items, $limit=false)
{
    usort($items, 'sortTime');
    $items = limitImages($items);
    return $items;
}

function sortTime($a,$b)
{
    return $b->time - $a->time;
}

function limitImages($items)
{
    $i = 1;
    foreach ($items as $key => $item)
    {
        if($item->type == 'Folder') continue;
        if($i > 20){
        unset($items[$key]);
        } else {
            $items[$key]->url = str_replace('laravel-filemanager/', '', $item->url);
            $items[$key]->thumb = str_replace('laravel-filemanager/', '', $item->thumb);
        }

        $i++;
    }
    return $items;
}


function sortFileManagerList2($files, $limit=false)
{
    $ftime = [];
    foreach ($files as $key => $row)
    {
        $ftime[$key] = $row['updated'];
    }
    array_multisort($ftime, SORT_DESC, $files);
    if($limit) $files = limitArrayAmount($files);
    return $files;
}

function limitArrayAmount($files)
{
    $files = array_slice($files, 0, 20);
    return $files;
}