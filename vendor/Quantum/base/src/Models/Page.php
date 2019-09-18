<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'subtitle', 'content', 'area', 'status', 'route', 'preContent', 'bodyClass', 'pageCss', 'pageJs', 'showBreadcrumbs', 'hideMenu', 'tenant'];

    protected $with = ['meta'];

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

    public function meta()
    {
        return $this->hasOne(\Quantum\base\Models\PageMeta::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function revisions()
    {
        return $this->hasMany('Quantum\base\Models\PageRevisions', 'pages_id')->orderBy('id', 'desc');
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
}
