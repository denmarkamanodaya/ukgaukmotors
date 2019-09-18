<?php


function hasPreview($preview)
{
    if(Auth::user() && Auth::user()->hasRole(Settings::get('main_content_role'))) return '';
    if($preview == 1) return '<span class="bookPreview">Preview</span>';
}