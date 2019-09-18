<?php

namespace Quantum\tickets\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $fillable = ['ticket_department_id', 'ticket_status_id', 'user_id', 'email', 'staff_id', 'title', 'content', 'public_key'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tickets';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.tickets'));
    }

    public function status()
    {
        return $this->belongsTo('Quantum\tickets\Models\TicketStatus', 'ticket_status_id');
    }

    public function department()
    {
        return $this->belongsTo('Quantum\tickets\Models\TicketDepartment', 'ticket_department_id');
    }

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User', 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo('Quantum\base\Models\User', 'staff_id');
    }

    public function replies()
    {
        return $this->hasMany('Quantum\tickets\Models\TicketReplies')->with('user', 'staff')->orderBy('updated_at', 'DESC');
    }
}
