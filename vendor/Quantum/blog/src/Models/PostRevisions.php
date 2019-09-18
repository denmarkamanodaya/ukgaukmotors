<?php

namespace Quantum\blog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class PostRevisions extends Eloquent {

    protected $fillable = ['title', 'slug', 'content', 'summary', 'area', 'status', 'publishOnTime', 'publish_on', 'user_id', 'main_category_id', 'post_category_id', 'rev_type'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_revisions';

    protected $dates = ['publish_on'];

    public function post()
    {
        return $this->belongsTo('Quantum\blog\Models\Posts', '');
    }

    public function parentCategory()
    {
        return $this->belongsTo('Quantum\blog\Models\Categories', 'main_category_id');
    }

    public function category()
    {
        return $this->belongsTo('Quantum\blog\Models\Categories', 'post_category_id');
    }

    public function tags()
    {
        return $this->belongsToMany('Quantum\blog\Models\Tags', 'posts_site_tags', 'site_tags_id', 'posts_id');
    }

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User');
    }

    public function roles()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class, 'posts_role', 'posts_id');
    }

}
