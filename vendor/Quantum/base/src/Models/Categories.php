<?php

namespace Quantum\base\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Categories extends Eloquent {

    protected $fillable = ['name', 'slug', 'parent_id', 'description', 'icon', 'user_id', 'system', 'area', 'tenant'];
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'site_categories';

    //protected $with = 'children';

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function roles()
    {
        return $this->hasMany('Quantum\base\Models\Role');
    }

    public function posts()
    {
        return $this->hasMany('Quantum\blog\Models\Posts');
    }

    public function postCount()
    {
        $area = ['public'];
        if(\Auth::check()) array_push($area, 'members');
        $userRoles = $this->getRoles($area);
        return $this->getPostCount($area, $userRoles);
    }

    public function childrenCount()
    {
        return $this->hasOne('Quantum\base\Models\Categories', 'parent_id')
            ->selectRaw('parent_id, count(*) as aggregate')
            ->groupBy('parent_id');
    }

    public function children()
    {
        return $this->hasMany(\Quantum\base\Models\Categories::class, 'parent_id')->orderBy('name', 'ASC');
    }

    public function parent()
    {
        return $this->belongsTo(\Quantum\base\Models\Categories::class, 'parent_id');
    }

    private function getPostCount($area, $userRoles)
    {
        return $this->hasOne('Quantum\blog\Models\Posts', 'post_category_id')
            ->whereIn('area',$area)
            ->where('post_group', 'admin')
            ->where('status', 'published')
            ->where(function($query) use($userRoles) {
                $query->whereHas('roles', function($query) use($userRoles) {
                    $query->whereIn("name", $userRoles);
                });
            })
            ->selectRaw('post_category_id, count(*) as aggregate')
            ->groupBy('post_category_id');
    }

    private function getRoles($area=[])
    {
        $userRoles = [];
        if(in_array('members', $area)){
            if(!\Auth::check()) abort(404);
            $userRoles = \Auth::user()->roles->pluck('name')->toArray();
        }

        array_push($userRoles, 'guest');
        return $userRoles;
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
    
}
