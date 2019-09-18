<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookMeta extends Model
{
    protected $fillable = ['book_id', 'featured_image', 'description', 'keywords', 'type', 'robots'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'book_meta';

    public function post()
    {
        return $this->belongsTo('App\Models\Book');
    }
}
