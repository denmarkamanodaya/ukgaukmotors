<?php

namespace App\Services;

/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserShortcodeService.php
 **/
class UserShortcodeService
{
    protected $content;
    protected $user;

    public function doReplace($type, $content, $user=null)
    {
        $this->content = $content;
        $this->user = $user;
        if($type == 'email') $this->doEmail();
        if($type == 'page') $this->doPage();
        return $this->content;
    }

    private function doContentreplace($search, $replacement)
    {
        $this->content = str_replace('['.$search.']', $replacement, $this->content);
    }

    public function doEmail()
    {
        //$this->doContentreplace('davetest', 'i am a test '.$this->user->username);
    }

    public function doPage()
    {

    }

}