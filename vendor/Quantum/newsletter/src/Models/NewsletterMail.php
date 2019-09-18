<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterMail extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['newsletter_id', 'message_type', 'subject', 'html_message', 'plain_message', 'position', 'interval_amount', 'interval_type'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_mail';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletterMail'));
    }

    public function newsletter()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\Newsletter::class);
    }
}
