<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'system'];

    public function user()
    {
        return $this->belongsToMany(\Quantum\base\Models\User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(\Quantum\base\Models\Permission::class);
    }

    public function page()
    {
        return $this->belongsToMany(\Quantum\base\Models\Page::class);
    }

    public function givePermissionTo($permission)
    {
        if(is_string($permission))
        {
            $permission = \Quantum\base\Models\Permission::where('name', $permission)->first();
        }
        $this->permissions()->save($permission);
        return $this;
    }

    public function revokePermissionTo($permission)
    {
        if(is_string($permission))
        {
            $permission = \Quantum\base\Models\Permission::where('name', $permission)->first();
        }
        $this->permissions()->detach($permission);
        return $this;
    }
}
