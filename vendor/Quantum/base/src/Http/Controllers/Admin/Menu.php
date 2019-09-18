<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Http\Requests\Admin\CreateMenuRequest;
use Quantum\base\Http\Requests\Admin\CreateMenuItemRequest;
use Quantum\base\Http\Requests\Admin\EditMenuRequest;
use Quantum\base\Http\Requests\Admin\MenuItemPositionRequest;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Role;
use Quantum\base\Services\MenuItemService;
use Quantum\base\Services\MenuService;
use Quantum\base\Services\PageService;
use Quantum\base\Services\PermissionGroupService;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Menu extends Controller
{

    /**
     * @var MenuService
     */
    private $menuService;
    /**
     * @var MenuItemService
     */
    private $menuItemService;
    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(MenuService $menuService, MenuItemService $menuItemService, PageService $pageService)
    {
        $this->menuService = $menuService;
        $this->menuItemService = $menuItemService;
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = \Quantum\base\Models\Menu::tenant()->orderBy('id', 'ASC')->paginate(20);
        $roles = Role::where('name', '!=', 'super_admin')->pluck('title', 'id');

        return view('base::admin.Menu.index', compact('menus', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMenuRequest $request)
    {
        $menu = $this->menuService->createMenu($request);
        return redirect('admin/menu/'.$menu->id.'/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, PermissionGroupService $permissionGroupService)
    {
        $menu = \Quantum\base\Models\Menu::whereId($id)->tenant()->firstOrFail();
        $roles = Role::where('name', '!=', 'super_admin')->pluck('title', 'id');
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        $permissionList = $permissionGroupService->groupList();
        $itemList = $this->menuService->getEdit($menu->id);
        $pages = $this->pageService->getPageList(null,false);
        return view('base::admin.Menu.edit', compact('menu', 'roles', 'permissionList', 'itemList', 'pages', 'fajson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditMenuRequest $request, $id)
    {
        $this->menuService->updateMenu($id, $request);
        return redirect('admin/menu/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->menuService->deleteMenu($id);
        return redirect('admin/menu/');
    }

    public function storeList(CreateMenuItemRequest $createMenuItemRequest)
    {
        $menuItem = $this->menuItemService->createItem($createMenuItemRequest);
        return redirect('admin/menu/'.$menuItem->menu_id.'/edit');
    }

    public function ItemPosition(MenuItemPositionRequest $menuItemPositionRequest)
    {
        $menu = $this->menuItemService->updatePosition($menuItemPositionRequest);
        return response()->json(['status' => 'success','message' => 'Item position has been changed.', 'data' => $menu]);
    }

    public function getItem(\Quantum\base\Http\Requests\Admin\GetItemRequest $getItemRequest, PermissionGroupService $permissionGroupService)
    {
        $item = MenuItems::whereId($getItemRequest->itemId)->first();
        $menu = \Quantum\base\Models\Menu::tenant()->find($getItemRequest->menu);
        //$icons = fontAwesomeList();
        $permissionList = $permissionGroupService->groupList();
        $data['data'] = \View::make('base::admin.Menu.partials.ItemEdit', compact('item', 'menu', 'permissionList'))->render();
        $data['status'] = 'success';
        return $data;
    }

    public function ItemUpdate(\Quantum\base\Http\Requests\Admin\EditMenuItemRequest $editMenuItemRequest)
    {
        $menuItem = $this->menuItemService->editItem($editMenuItemRequest);
        return redirect('admin/menu/'.$menuItem->menu_id.'/edit');
    }

    public function ItemDelete($id)
    {
        $menu_id = $this->menuItemService->deleteItem($id);
        return redirect('admin/menu/'.$menu_id.'/edit');
    }
    
}
