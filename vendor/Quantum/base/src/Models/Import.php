<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'import';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'import_area', 'import_type', 'content', 'complete', 'tenant'];

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }


    public function categories()
    {
        return $this->hasMany(\Quantum\base\Models\ImportCategories::class);
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }

}
