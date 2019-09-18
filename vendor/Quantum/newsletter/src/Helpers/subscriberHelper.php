<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : subscriberHelper.php
 **/

function is_checked_newsletter($newsletter, $selectedNewsletter)
{
    if(!$selectedNewsletter) return false;
    if($newsletter == $selectedNewsletter->slug) return true;
    return false;
}

function is_user($subscriber)
{
    if($subscriber->user) return '<i class="far fa-check"></i>';
}

function is_subscribed($newsletter, $subscribed)
{
    if($subscribed->contains('newsletter_id', $newsletter->id))
    {
        return true;
    }
    return false;
}

function isNoJoin($id, $nojoin)
{
    if($nojoin->contains('id', $id))
    {
        return true;
    }
    return false;
}