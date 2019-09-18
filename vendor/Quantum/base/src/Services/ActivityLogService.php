<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ActivityLogService.php
 **/

namespace Quantum\base\Services;

use Request;
use Quantum\base\Models\ActivityLog as ActivityLog;
use Quantum\base\Models\ActivityLogModel as ActivityLogModel;
use Carbon\Carbon;

class ActivityLogService
{
    protected $extra_filters = array();

    public function setExtraFilter($name, $value) {
        $this->extra_filters[$name] = $value;
    }

    public function log($text, $obj = null, $user = null, $extra = array())
    {
        if ($user == null) $user = \Auth::user();
        
        $log = new ActivityLog();

        if ($obj) {
            $activitylogModel = ActivityLogModel::firstOrCreate(['model' => get_class($obj)]);
            $log->model()->associate($activitylogModel);
            $log->model_id = $obj->id;
        }

        $log->ip_address = Request::getClientIp();
        $log->text       = $text;
        $log->user_id    = isset($user->id) ? $user->id : null;

        foreach ($this->extra_filters as $name => $value) {
            $log->$name = $value;
        }

        foreach ($extra as $name => $value) {
            $log->$name = $value;
        }
        
        $this->clearCache();
        return $log->save();
    }

    public function query($extras=[])
    {
        $q = ActivityLog::query();

        foreach ($this->extra_filters as $name => $value) {
            $q->where($name, $value);
        }

        foreach ($extras as $name => $value) {
            $q->where($name, $value);
        }

        return $q;
    }

    public function clean($older_than_days)
    {
        ActivityLog::whereDate('created_at', '<', Carbon::today()->subDays($older_than_days)->toDateString())->delete();
    }
    
    public function clearCache()
    {
        \Cache::forget('activityLog');
    }
}