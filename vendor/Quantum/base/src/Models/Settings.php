<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'data', 'tenant'];

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
}
