<?php

namespace Quantum\tickets\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReplies extends Model
{
    protected $fillable = ['tickets_id', 'user_id', 'title', 'content', 'staff_id'];

    protected $touches = ['ticket'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticket_replies';

    //protected $touches = ['ticket'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.ticketsReplies'));
    }

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User', 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo('Quantum\base\Models\User', 'staff_id');
    }

    public function ticket()
    {
        return $this->belongsTo('Quantum\tickets\Models\Tickets', 'tickets_id');
    }
}
