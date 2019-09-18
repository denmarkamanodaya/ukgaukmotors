<?php

/**
 * Prepare log data for output
 * @param $data
 * @return string
 */
function logData($data)
{
    $out = '<ul class="logData">';
    $data = maybe_unserialize($data);
    if(is_array($data))
    {
        foreach($data as $key => $value)
        {
            $out .= '<li>'.camelToReadable($key).': '.$value.'</li>';
        }
    } else {
        $out .= '<li>'.$data.'</li>';
    }
    $out .= '</ul>';
    return $out;
}

/**
 * Prepare log relations for output
 * @param $user_id
 * @param $catalogue_id
 * @param $mining_id
 * @return string
 */
function logRelations($user_id, $catalogue_id, $mining_id)
{
    $out = '<ul class="logRelations">';
    if($user_id != '') $out .= '<li> User : '.$user_id.'</li>';
    if($catalogue_id != '') $out .= '<li> Catalogue : '.$catalogue_id.'</li>';
    if($mining_id != '') $out .= '<li> Mining : '.$mining_id.'</li>';
    $out .= '</ul>';
    return $out;
}