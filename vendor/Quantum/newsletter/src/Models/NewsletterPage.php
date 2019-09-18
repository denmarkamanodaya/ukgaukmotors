<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterPage extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['newsletter_id', 'page_type', 'content'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_pages';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletterPages'));
    }

    public function newsletter()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\Newsletter::class);
    }
}
