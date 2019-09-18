<?php

function isDefaultStatus($status)
{
    if($status->default == 1) return '<i class="far fa-check"></i>';
    return '';
}

function statusIcon($status)
{
    if($status->icon != '') return '<i class="'.$status->icon.'"></i>';
}

function ticketCountIcon($area, $ticketCount)
{
    if(array_key_exists($area, $ticketCount))
    {
        if($ticketCount[$area] == '0') return '';
        $out = '&nbsp;<span class="badge">'.$ticketCount[$area].'</span>&nbsp;';
        $out .= newTickets($area, $ticketCount);
        return $out;
    }
}

function newTickets($area, $ticketCount)
{
    $login = \Auth::user()->previous_login_date;
    if(!$login) return;
    if(!isset($ticketCount[$area.'_Latest'])) return '';
    if($ticketCount[$area.'_Latest']->gt($login)){
        return '&nbsp;<span class="badge badge-warning" style="position: relative!important;">Updated</span>&nbsp;';
    }
}
