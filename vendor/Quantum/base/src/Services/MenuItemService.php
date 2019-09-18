<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : MenuItemService.php
 **/

namespace Quantum\base\Services;


use Quantum\base\Models\MenuItems;
use Quantum\base\Services\MenuService;

class MenuItemService
{

    /**
     * @var \Quantum\base\Services\MenuService
     */
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function createItem($request)
    {
        $menuItem = MenuItems::create($request->all());
        flash('Success : The menu item has been created.')->success();
        return $menuItem;
    }

    public function updatePosition($request)
    {
        $position = json_decode($request->position);
        $i = 1;
        foreach($position as $item)
        {

            $this->updateItemPosition($item->id,0,$i);
            if(isset($item->children)){ $this->processChildren($item->children, $item->id); }
            $i++;
        }
        $this->clearCache($request->menu_id);
        return;
    }

    private function updateItemPosition($itemId,$parent,$position)
    {

        $menuItem = MenuItems::find($itemId);
        if($menuItem)
        {
            $menuItem->parent_id = $parent;
            $menuItem->position = $position;
            $menuItem->save();
        }
        return;
    }

    private function processChildren($item, $itemId)
    {
        $parent = MenuItems::whereId($itemId)->first();
        $i = 1;
        foreach($item as $child)
        {
            $this->updateItemPosition($child->id,$parent->id,$i);
            if(isset($child->children)){ $this->processChildren($child->children, $child->id); }
            $i++;
        }

        return;
    }

    private function clearCache($id)
    {
        $this->menuService->clearCache($id);
    }

    public function editItem($request)
    {
        $item = MenuItems::findOrFail($request->id);
        $item->fill($request->all());
        $item->save();

        $this->clearCache($request->menu_id);
        flash('Success : The menu item has been updated.')->success();
        return $item;
    }

    public function deleteItem($id)
    {
        $item = MenuItems::findOrFail($id);
        $menu_id = $item->menu_id;
        $item->delete();
        flash('Success : The menu item has been deleted.')->success();
        return $menu_id;
    }



}