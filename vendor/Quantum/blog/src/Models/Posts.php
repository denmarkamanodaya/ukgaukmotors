<?php

namespace Quantum\blog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use Sofa\Eloquence\Eloquence;

class Posts extends Eloquent {

    use Eloquence;

    protected $fillable = ['title', 'slug', 'content', 'summary', 'area', 'status', 'publishOnTime', 'publish_on', 'user_id', 'main_category_id', 'post_category_id', 'sticky', 'featured', 'tenant'];

    protected $searchableColumns = ['title', 'content'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';
    
    protected $dates = ['publish_on'];

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function meta()
    {
        return $this->hasOne('Quantum\blog\Models\PostMeta');
    }

    public function parentCategory()
    {
        return $this->belongsTo('Quantum\base\Models\Categories', 'main_category_id');
    }

    public function category()
    {
        return $this->belongsTo('Quantum\base\Models\Categories', 'post_category_id');
    }

    public function tags()
    {
        return $this->belongsToMany('Quantum\base\Models\Tags', 'posts_site_tags', 'posts_id', 'site_tags_id');
    }

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User');
    }

    public function roles()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class);
    }

    public function revisions()
    {
        return $this->hasMany('Quantum\blog\Models\PostRevisions')->orderBy('id', 'desc');
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }

}
