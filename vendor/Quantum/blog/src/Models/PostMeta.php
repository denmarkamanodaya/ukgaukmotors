<?php

namespace Quantum\blog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class PostMeta extends Eloquent {

    protected $fillable = ['post_id', 'featured_image', 'description', 'keywords', 'type', 'meta_title', 'robots'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts_meta';

    public function post()
    {
        return $this->belongsTo('Quantum\blog\Models\Posts');
    }

}
