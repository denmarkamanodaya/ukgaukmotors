<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterImportQueue extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['newsletter_id', 'start_responder', 'send_welcome', 'csvfile', 'startAt', 'error', 'completed'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_import_queue';

    public function newsletter()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\Newsletter::class);
    }
}
