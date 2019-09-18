<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'permission_group_id', 'position'];

    public function user()
    {
        return $this->belongsToMany(\Quantum\base\Models\User::class);
    }

    public function group()
    {
        return $this->belongsTo(\Quantum\base\Models\PermissionGroup::class);
    }

    public function roles()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class);
    }
}
