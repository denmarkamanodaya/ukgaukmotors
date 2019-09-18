<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['newsletter_id', 'user_id', 'first_name', 'last_name', 'email', 'email_confirmed', 'sequence', 'sequence_send_on',
        'newsletter_import_id', 'sub_code', 'bounced', 'complaint', 'active'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_subscribers';

    protected $dates = ['sequence_send_on'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletterSubscriber'));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($subscriber){
            $subscriber->sub_code = str_random(30);
        });
    }

    public function newsletter()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\Newsletter::class);
    }

    public function import()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\NewsletterImport::class);
    }

    public function user()
    {
        return $this->belongsTo(\Quantum\base\Models\User::class);
    }

    public  function scopeLike($query, $field, $value){
        if($value == '') return $query;
        return $query->where($field, 'LIKE', "%$value%");
    }

    public  function scopeSearch($query, $field, $value){
        if($value == '' || $value == 0) return $query;
        return $query->where($field, $value);
    }

}
