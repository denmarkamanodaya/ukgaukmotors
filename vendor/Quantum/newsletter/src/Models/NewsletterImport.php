<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterImport extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['newsletter_id', 'import_count'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_import';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletterImport'));
    }

    public function newsletter()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\Newsletter::class);
    }

    public function subscribers()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterSubscriber::class);
    }
}
