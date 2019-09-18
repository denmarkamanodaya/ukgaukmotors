<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'mail_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'subject', 'content_html', 'content_text'];

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User');
    }
}
