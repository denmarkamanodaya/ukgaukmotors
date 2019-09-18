<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_id', 'type', 'parent_id', 'icon', 'url', 'title', 'permission', 'position'];

    public function menu()
    {
        return $this->belongsTo(\Quantum\base\Models\Menu::class);
    }
    public function scopeMenuId($query, $menuid)
    {
        return $query->where('menu_id', $menuid);
    }


    public function scopeOrdered($query)
    {
        return $query->orderBy('parent_id', 'ASC')->orderBy('position', 'ASC');
    }
}
