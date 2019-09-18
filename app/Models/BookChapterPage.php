<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class BookChapterPage extends Model
{
    use Sluggable;

    protected $fillable = ['book_chapters_id', 'book_id', 'title', 'slug', 'content', 'position', 'featured_image', 'public_view'];
    protected $touches = ['chapter'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'book_page';

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\BookChapter');
    }

    public function revisions()
    {
        return $this->hasMany('App\Models\BookChapterPageRevision', 'book_page_id');
    }
}
