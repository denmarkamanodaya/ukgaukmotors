<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'version', 'tenant'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'modules';

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
