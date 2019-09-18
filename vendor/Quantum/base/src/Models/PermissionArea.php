<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionArea extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_areas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'position', 'system'];

    protected $with = ['permissiongroups'];

    public function permissiongroups()
    {
        return $this->hasMany(\Quantum\base\Models\PermissionGroup::class);
    }
}
