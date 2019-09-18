<?php

/**
 * Set menu active path
 * @param $path
 * @param string $active
 * @return string
 */
function menu_set_active($path, $active = 'active') {

    return call_user_func_array('Request::is', (array)$path) ? $active : '';

}

/**
 * Prepare breadcrumbs for output
 * @param $bc
 * @return bool|string
 */
function breadcrumbs($bc)
{
    if(!is_array($bc)) return false;
    $bread = '<ul class="breadcrumb breadcrumb-caret position-right">';
    $i = 1;
    foreach($bc as $linkname => $linkto)
    {
        if($i == 1) { $class = 'clip-file'; } else { $class = ''; }

        if($linkto == 'is_current' || $linkto == '')
        {
            $bread .= '<li class="active">';
            $bread .= '<i class="active"></i>';
            $bread .= $linkname;
        } 
        elseif($linkto == 'go_back')
        {
            $bread .= '<li>';
            $bread .= '<i class="'.$class.'"></i>';
            $bread .= '<a href="javascript:history.back();">'.$linkname.'</a>&nbsp;';
        } 
        else {
            $bread .= '<li>';
            $bread .= '<i class="'.$class.'"></i>';
            $bread .= '<a href="'.url($linkto).'">'.$linkname.'</a>&nbsp;';
        }

        $bread .= '</li>';
        $i++;
    }
    $bread .= '</ul>';
    return $bread;
}