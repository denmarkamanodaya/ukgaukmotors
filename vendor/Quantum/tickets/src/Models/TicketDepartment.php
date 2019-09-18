<?php

namespace Quantum\tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class TicketDepartment extends Model
{
    use Sluggable;

    protected $fillable = ['name', 'slug', 'parent_id', 'description', 'icon', 'system', 'default', 'colour'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticket_department';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.ticketsDepartment'));
    }
}
