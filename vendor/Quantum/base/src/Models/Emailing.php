<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Emailing extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['title', 'subject', 'content_html', 'content_text', 'system', 'tenant'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emails';

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
