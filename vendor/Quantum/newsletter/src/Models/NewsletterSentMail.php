<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSentMail extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['newsletter_id', 'subject', 'html_message', 'plain_message', 'sent_count', 'opened_count', 'send_on', 'sent_on', 'mail_code', 'in_progress', 'active', 'personalise', 'bcc_amount', 'bcc_to_email'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_sent_mail';

    protected $dates = ['send_on', 'sent_on'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletterSentMail'));
    }

    public function newsletter()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\Newsletter::class);
    }

    public  function scopeSearchNewsletter($query, $value){
        if($value == '' || $value == 0) return $query;
        return $query->where('newsletter_id', $value);
    }
}
