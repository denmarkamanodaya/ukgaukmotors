<?php

namespace Quantum\base\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Tags extends Eloquent {

    protected $fillable = ['name', 'slug', 'user_id', 'area', 'tenant'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'site_tags';

    public function posts()
    {
        return $this->belongsToMany('Quantum\blog\Models\Posts', 'posts_site_tags', 'site_tags_id', 'posts_id');
    }

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
