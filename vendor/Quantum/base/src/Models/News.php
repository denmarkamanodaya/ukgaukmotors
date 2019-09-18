<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'news';

    protected $with = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'area', 'status', 'type', 'header_content', 'system', 'tenant'];

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }


    public function roles()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
}
