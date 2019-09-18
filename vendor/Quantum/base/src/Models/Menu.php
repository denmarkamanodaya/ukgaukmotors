<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'role_id', 'parent_start',
            'parent_end', 'child_start', 'child_end', 'tenant'];

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function items()
    {
        return $this->hasMany(\Quantum\base\Models\MenuItems::class);
    }

    public function role()
    {
        return $this->belongsTo(\Quantum\base\Models\Role::class);
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
}
