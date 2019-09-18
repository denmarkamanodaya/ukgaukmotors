<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Book extends Model
{
    use Sluggable;

    protected $fillable = ['title', 'slug', 'front_cover', 'back_cover', 'content', 'details', 'preContent', 'bodyClass', 'pageCss', 'pageJs', 'showBreadcrumbs', 'hideMenu'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'book';

    protected $with = 'meta';

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->orderBy('position', 'ASC');
    }

    public function meta()
    {
        return $this->hasOne('App\Models\BookMeta');
    }




}
