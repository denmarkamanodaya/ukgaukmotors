<?php

namespace Quantum\base\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class PageRevisions extends Eloquent {

    protected $fillable = ['pages_id', 'title', 'subtitle', 'content', 'area', 'status', 'route', 'preContent'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'page_revisions';


    public function page()
    {
        return $this->belongsTo('Quantum\base\Models\Page');
    }

}
