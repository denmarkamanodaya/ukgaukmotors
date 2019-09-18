<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class ImportCategories extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'import_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['import_id', 'name'];

    public function import()
    {
        return $this->belongsTo(\Quantum\base\Models\Import::class);
    }

}
