<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'permission_area_id', 'position', 'system'];

    protected $with = ['permissions'];

    public function permissionarea()
    {
        return $this->belongsTo(\Quantum\base\Models\PermissionArea::class);
    }

    public function permissions()
    {
        return $this->hasMany(\Quantum\base\Models\Permission::class);
    }
}
