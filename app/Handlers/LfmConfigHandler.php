<?php

namespace App\Handlers;

class LfmConfigHandler
{
    public function userField()
    {
        return auth()->user()->username;
    }
}
