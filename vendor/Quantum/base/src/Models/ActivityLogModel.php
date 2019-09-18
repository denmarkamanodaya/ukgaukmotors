<?php

namespace Quantum\base\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class ActivityLogModel extends Eloquent{

    protected $fillable = ['model'];

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_log_model';


    public function userActivityLogs()
    {
        return $this->hasMany('Quantum\base\Models\ActivityLog');
    }
}
