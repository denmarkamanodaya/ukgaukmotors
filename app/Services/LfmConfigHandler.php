<?php

namespace App\Services;

class LfmConfigHandler
{
    public function userField()
    {
        return auth()->user()->username;
    }
}
