<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class BookChapter extends Model
{
    use Sluggable;

    protected $fillable = ['book_id', 'title', 'slug', 'featured_image', 'position'];
    protected $touches = ['book'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'book_chapters';

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function pages()
    {
        return $this->hasMany('App\Models\BookChapterPage', 'book_chapters_id')->orderBy('position', 'ASC');
    }
}
