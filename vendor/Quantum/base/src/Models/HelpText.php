<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class HelpText extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'helptext';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'area'];

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}
