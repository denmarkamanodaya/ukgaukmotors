<?php

namespace Quantum\tickets\Models;

use Illuminate\Database\Eloquent\Model;

class TicketUser extends Model
{
    protected $fillable = ['user_id', 'position', 'signature'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticket_user';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.ticketsUser'));
    }
}
