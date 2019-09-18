<?php

function isCalSelected($selectItem, $selected)
{
    if(!$selected || $selected == '') return false;
    $selected = explode(',', $selected);

    foreach ($selected as $selectedUnit)
    {
        if($selectedUnit == $selectItem) return true;
    }

    return false;
}

function isCalCategory($id,$selectedCategories)
{
    if(!$selectedCategories) return false;
    if($selectedCategories->count() == 0) return false;

    if($selectedCategories->where('id', $id)->first()) return ' checked="checked"';
    return false;
}