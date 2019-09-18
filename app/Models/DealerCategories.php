<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class DealerCategories extends Eloquent {

    protected $fillable = ['name', 'slug', 'parent_id', 'description', 'icon', 'user_id', 'system', 'area'];
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dealer_categories';

    protected $connection = 'mysql';

    public function children()
    {
        return $this->hasMany(\App\Models\DealerCategories::class, 'parent_id')->orderBy('name', 'ASC');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\DealerCategories::class, 'parent_id');
    }
    
}
