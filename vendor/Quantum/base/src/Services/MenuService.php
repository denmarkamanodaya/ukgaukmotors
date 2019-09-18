<?php

namespace Quantum\base\Services;


use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    private $menu;

    private $menuItems;

    private $build = [];

    private $menuBuild = '';

    private $currentParent = 0;

    private $sortedItems = [];

    private $user;


    public function show($menuId)
    {
        if (\Auth::check())
        {
            $this->user = \Auth::User();
            $cacheKey = 'Menu_'.str_slug($menuId).'_'.$this->user->id;
        } else {
            $this->user = 0;
            $cacheKey = 'Menu_'.str_slug($menuId).'_'.$this->user;
        }
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if( $menu = $this->buildMenu($menuId)){
            Cache::forever($cacheKey, $menu);
            return $menu;
        }

        return false;
    }
    
    private function reset()
    {
        $this->menu = '';
        $this->menuItems = '';
        $this->build = [];
        $this->menuBuild = '';
        $this->currentParent = 0;
        $this->sortedItems = [];
    }

    private function menu_access_allowed()
    {
        //public menu
        if($this->menu->role_id == 4) return true;
        //private menu
        if(is_object($this->user) && $this->user->hasRole($this->menu->role)) return true;
        //Not Allowed
        return false;
    }

    private function buildMenu($menuId)
    {
        $this->reset();
        if(!$this->menu = Menu::with('items')->where('title', $menuId)->tenant()->first())
        {
           return false;  
        }

        if(!$this->menu_access_allowed()) return false;
        $this->menuItems = MenuItems::Ordered()->MenuId($this->menu->id)->get();

        $this->constructMenu();
        return $this->menuBuild;
    }

    private function constructMenu()
    {
        foreach($this->menuItems as $item)
        {
            if(!isset($this->sortedItems[$item->parent_id])) $this->sortedItems[$item->parent_id] = [];

                $this->sortedItems[$item->parent_id][$item->id] = [];
                $this->sortedItems[$item->parent_id][$item->id]['id'] = $item->id;
                $this->sortedItems[$item->parent_id][$item->id]['icon'] = $item->icon;
                $this->sortedItems[$item->parent_id][$item->id]['url'] = $item->url;
                $this->sortedItems[$item->parent_id][$item->id]['title'] = $item->title;
                $this->sortedItems[$item->parent_id][$item->id]['permission'] = $item->permission;
                $this->sortedItems[$item->parent_id][$item->id]['type'] = $item->type;
                $this->sortedItems[$item->parent_id][$item->id]['position'] = $item->position;
        }
        $this->startMenuBuild();
        $this->menuBuildItems();
        $this->endBuildMenu();
    }

    private function startMenuBuild()
    {
        $this->menuBuild = $this->menu->parent_start;
    }

    private function endBuildMenu()
    {
        $this->menuBuild .= $this->menu->parent_end;
    }

    private function isPublic()
    {
        return $this->menu->role_id == 4 ? true : false;
    }

    private function menuBuildItems()
    {
        foreach($this->sortedItems[0] as $item)
        {
            if(!$this->isPublic())
            {
                if($item['permission'] != '')
                {
                    if (\Auth::user()->cannot($item['permission'])) continue;
                }
            }

            if($item['type'] == 'normal') $this->addNormalItem($item);
            if($item['type'] == 'dropdown') $this->addDropdownItem($item);
        }
    }

    private function addNormalItem($item)
    {
        $this->menuBuild .= '<li><a href="'.$item['url'].'">';
        if($item['icon'] == 0){
            $this->menuBuild .= '<i class="'.$item['icon'].' position-left"></i> '; 
        }
        $this->menuBuild .= $item['title'].'</a></li>';
        return;
    }

    private function addDropdownItem($item)
    {
        $this->menuBuild .= '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        if($item['icon'] == 0){
            $this->menuBuild .= '<i class="'.$item['icon'].' position-left"></i> ';
        }
        $this->menuBuild .= $item['title'].'<span class="caret"></span></a>';
        
        $this->menuBuild .= '<ul class="dropdown-menu">';

        $this->addDropdownChildren($item);
        $this->menuBuild .= '</ul></li>';
        return;
    }

    private function addDropdownChildren($parent)
    {
        if(!isset($this->sortedItems[$parent['id']])) return;

        if(count($this->sortedItems[$parent['id']]) > 0)
        {
            foreach($this->sortedItems[$parent['id']] as $item)
            {
                if(!$this->isPublic())
                {
                    if($item['permission'] != '')
                    {
                        if (\Auth::user()->cannot($item['permission'])) continue;
                    }
                }

                if($item['type'] == 'normal') $this->addNormalItem($item);
                if($item['type'] == 'dropdown-header') $this->addDropdownHeader($item);
                if($item['type'] == 'dropdown-submenu') $this->addDropdownSubMenu($item);
            }
        }
    }


    private function addDropdownHeader($item)
    {
        $this->menuBuild .= '<li class="dropdown-header">'.$item['title'].'</li>';
        return;
    }


    private function addDropdownSubMenu($item)
    {
        $this->menuBuild .= '<li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        if($item['icon'] == 0){
            $this->menuBuild .= '<i class="'.$item['icon'].' position-left"></i> ';
        }
        $this->menuBuild .= $item['title'].'</a>';
        
        $this->menuBuild .= '<ul class="dropdown-menu">';

        $this->addDropdownChildren($item);

        $this->menuBuild .= '</ul></li>';
    }

    public function clearCache($menuid = false)
    {
        if($menuid) $this->clearCacheMenu($menuid);
        else $this->clearCacheAll();
    }

    public function clearCacheUser($userId)
    {
        $menus = \Quantum\base\Models\Menu::tenant()->get();
        foreach($menus as $menu) {
            $cacheKey = 'Menu_' . str_slug($menu->title) . '_' . $userId;
            Cache::forget($cacheKey);
        }
    }
    
    public function clearCacheAll()
    {
        $users = \Quantum\base\Models\User::all();
        $menus = \Quantum\base\Models\Menu::tenant()->get();
        foreach($menus as $menu) {
            foreach ($users as $user) {
                $cacheKey = 'Menu_' . str_slug($menu->title) . '_' . $user->id;
                Cache::forget($cacheKey);
            }
            $cacheKey = 'Menu_' . $menu->id . '_0';
            Cache::forget($cacheKey);
        }
    }

    private function clearCacheMenu($menuId)
    {
        $users = \Quantum\base\Models\User::all();

        foreach ($users as $user) {
            $cacheKey = 'Menu_' . str_slug($menuId) . '_' . $user->id;
            Cache::forget($cacheKey);
        }
        $cacheKey = 'Menu_' . str_slug($menuId) . '_0';
        Cache::forget($cacheKey);
    }



    public function updateMenu($id, $request)
    {
        if($menu = Menu::tenant()->find($id))
        {
            $menu->fill($request->all());
            $menu->save();

            $this->clearCacheMenu($menu->id);
            flash('Success : The menu has been updated.')->success();
            \Activitylogger::log('Admin - Updated Menu : '.$menu->title, $menu);
        }

        return;
    }

    public function createMenu($request)
    {
        $menu = Menu::create($request->all());
        \Activitylogger::log('Admin - Created Menu : '.$menu->title, $menu);
        return $menu;
    }

    public function deleteMenu($id)
    {
        $menu = Menu::tenant()->where('id', $id)->firstOrFail();
        if(!$menu->system)
        {
            \Activitylogger::log('Admin - Deleted Menu : '.$menu->title, $menu);
            $menu->delete();
            flash('Success : The menu has been deleted.')->success();
            return;
        }

        flash('Error : The menu has not been deleted.')->error();
        return;
    }

    public function getEdit($menuid)
    {
        $this->menu = Menu::tenant()->where('id', $menuid)->firstOrFail();
        $this->menuItems = MenuItems::where('menu_id', $this->menu->id)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->get();
        $this->prepareEditMenu();
        $this->buildEditMenu();
        return $this->menuBuild;
    }

    private function prepareEditMenu()
    {
        $this->sortedItems = [];
        foreach($this->menuItems as $item)
        {
            if(!isset($this->sortedItems[$item->parent_id])) $this->sortedItems[$item->parent_id] = [];

            $this->sortedItems[$item->parent_id][$item->id] = [];
            $this->sortedItems[$item->parent_id][$item->id]['id'] = $item->id;
            $this->sortedItems[$item->parent_id][$item->id]['icon'] = $item->icon;
            $this->sortedItems[$item->parent_id][$item->id]['url'] = $item->url;
            $this->sortedItems[$item->parent_id][$item->id]['title'] = $item->title;
            $this->sortedItems[$item->parent_id][$item->id]['permission'] = $item->permission;
            $this->sortedItems[$item->parent_id][$item->id]['type'] = $item->type;
            $this->sortedItems[$item->parent_id][$item->id]['position'] = $item->position;
        }
    }

    private function buildEditMenu()
    {
        $this->menuBuild = '<ol class="dd-list" id="dd-list">';
        $this->menuBuildEditItems();
        $this->menuBuild .= '</ol>';
    }

    private function menuBuildEditItems()
    {
        if(count($this->sortedItems) == 0) return;
        foreach($this->sortedItems[0] as $item)
        {
            if($item['type'] == 'normal') $this->addEditNormalItem($item);
            if($item['type'] == 'dropdown') $this->addEditDropdownItem($item);
        }
    }

    private function addEditNormalItem($item)
    {
        $this->menuBuild .= '<li class="dd-item" data-id="'.$item['id'].'" id="'.$item['id'].'">
            <div class="dd-handle"><i class="'.$item['icon'].'"></i> '.$item['title'].'</div></li>';
        return;
    }

    private function addEditDropdownItem($item)
    {
        $this->menuBuild .= '<li class="dd-item" data-id="'.$item['id'].'" id="'.$item['id'].'">
            <div class="dd-handle" data-id="'.$item['id'].'"><i class="'.$item['icon'].'"></i> '.$item['title'].'</div>';
        $this->menuBuild .= '<ol class="dd-list" id="'.$item['id'].'">';
        $this->addEditDropdownChildren($item);
        $this->menuBuild .= '</ol></li>';
        return;
    }

    private function addEditDropdownChildren($parent)
    {
        if(!isset($this->sortedItems[$parent['id']])) return;

        if(count($this->sortedItems[$parent['id']]) > 0)
        {
            foreach($this->sortedItems[$parent['id']] as $item)
            {
                if($item['type'] == 'normal') $this->addEditNormalItem($item);
                if($item['type'] == 'dropdown-submenu') $this->addEditDropdownItem($item);
            }
        }
        return;
    }

    public function updateMenuIcons()
    {
        $menuItems = MenuItems::all();
        foreach ($menuItems as $menuItem)
        {
            if(starts_with($menuItem->icon, 'fa fa-'))
            {
                $menuItem->icon = str_replace('fa fa-', 'far fa-', $menuItem->icon);
                $menuItem->save();
            }
        }
    }

}