<?php

namespace Quantum\tickets\Models;

use Illuminate\Database\Eloquent\Model;

class TicketActivity extends Model
{
    protected $fillable = ['tickets_id', 'user_id', 'title', 'content'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticket_activity';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.ticketsActivity'));
    }
}
