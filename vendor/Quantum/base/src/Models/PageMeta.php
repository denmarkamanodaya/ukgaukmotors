<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class PageMeta extends Model
{
    protected $table = 'page_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page_id', 'featured_image', 'description', 'keywords', 'type', 'robots', 'meta_title'];
}
