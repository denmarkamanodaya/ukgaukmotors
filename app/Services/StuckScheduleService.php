<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : StuckScheduleService.php
 **/

namespace App\Services;


use Carbon\Carbon;

class StuckScheduleService
{

    public function check()
    {
        $path = storage_path('framework');
        foreach (glob($path.'/schedule-*') as $filename) {
            $isold = $this->isOld($filename);
            if($isold)
            {
                @unlink($filename);
                \Log::info('Stuck schedule cleared : '.$filename);
            }
        }
    }

    private function isOld($filename)
    {
        $fdate = filemtime($filename);
        $cFdate = Carbon::createFromTimestamp($fdate);
        $now = Carbon::now()->subHour();
        if($cFdate->lt($now)) return true;
        return false;
    }
}