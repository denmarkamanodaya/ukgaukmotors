<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookChapterPageRevision extends Model
{
    protected $fillable = ['book_page_id', 'book_chapters_id', 'title', 'slug', 'content', 'position', 'featured_image', 'public_view'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'book_page_revisions';


    public function page()
    {
        return $this->belongsTo('App\Models\BookChaptersPage');
    }
}
