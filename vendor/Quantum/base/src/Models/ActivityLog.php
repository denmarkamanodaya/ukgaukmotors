<?php

namespace Quantum\base\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class ActivityLog extends Eloquent {

    protected $fillable = ['user_id', 'activity_log_model_id', 'text', 'ip_address', 'model_id', 'tenant'];

    protected $relatedObject = null;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User');
    }

    public function model()
    {
        return $this->belongsTo('Quantum\base\Models\ActivityLogModel', 'activity_log_model_id');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('id','DESC');
    }

    public function scopeRelated($query, $obj)
    {
        if (is_object($obj)) {
            return $query->whereHas('model', function($q) use($obj){
                $q->where('model', get_class($obj));
            })->where('model_id', $obj->id);
        } else {
            return $query->whereHas('model', function($q) use($obj){
                $q->where('model', $obj);
            });
        }
    }

    public function scopeFromUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeCurrentUser($query)
    {
        $user = \Auth::user();
        return $this->scopeUser($query, $user);
    }

    public function hasValidObject()
    {
        try
        {
            $object = call_user_func_array($this->model->model . '::findOrFail', [$this->model_id]);
        }
        catch(\Exception $e)
        {
            return false;
        }

        $this->relatedObject = $object;

        return true;
    }

    public function getObject()
    {
        if(!$this->relatedObject && $this->model)
        {
            $hasObject = $this->hasValidObject();

            if(!$hasObject)
            {
                throw new \Exception(sprintf("No valid object (%s with ID %s) associated with this notification.", $this->model->model, $this->object_id));
            }
        }

        return $this->relatedObject;
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
}
