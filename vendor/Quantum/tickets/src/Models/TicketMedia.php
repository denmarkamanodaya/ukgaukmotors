<?php

namespace Quantum\tickets\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMedia extends Model
{
    protected $fillable = ['ticketable_id', 'ticketable_type', 'name', 'location', 'type'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticket_media';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.ticketsMedia'));
    }
}
