<?php

namespace Quantum\base\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\Role;
use Quantum\base\Http\Requests\Admin\CreateMembershipRequest;
use Quantum\base\Http\Requests\Admin\EditMembershipRequest;
use Quantum\base\Models\MembershipTypes;
use Quantum\base\Services\MembershipService;
use Quantum\base\Services\PageService;

class Membership extends Controller
{

    /**
     * @var PageService
     */
    private $pageService;
    /**
     * @var MembershipService
     */
    private $membershipService;

    public function __construct(PageService $pageService, MembershipService $membershipService)
    {
        $this->pageService = $pageService;
        $this->membershipService = $membershipService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $membershipTypes = MembershipTypes::tenant()->orderBy('position', 'ASC')->get();
        return view('base::admin.Membership.index', compact('membershipTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->pluck('title', 'id');
        $emaillist = Emailing::where('title', 'NOT LIKE', 'Notification -%')->tenant()->pluck('title', 'id')->toArray();
        $emails = ['Do Not Send An Email'];
        $emails = $emails + $emaillist;

        $pages = $this->pageService->getPageList(null, false);
        $memberPages = $this->pageService->getPageList('members', false);
        $position = [];
        for ($i = 1; $i <= 30; $i++) {
            $position[$i] = $i;
        }
        return view('base::admin.Membership.create', compact('roles', 'emails', 'pages', 'position', 'memberPages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMembershipRequest $request)
    {
        $this->membershipService->CreateMembership($request);
        return redirect('/admin/membership');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $membership = MembershipTypes::where('id', $id)->tenant()->firstOrFail();
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->pluck('title', 'id');
        $emaillist = Emailing::tenant()->pluck('title', 'id')->toArray();
        $emails = ['Do Not Send An Email'];
        $emails = $emails + $emaillist;

        $pages = $this->pageService->getPageList(null, false);
        $memberPages = $this->pageService->getPageList('members', false);
        $memRoles = $membership->roles()->pluck('role_id')->toArray();
        $memRolesRemove = $membership->rolesToRemove()->pluck('role_id')->toArray();
        $memRolesAdd = $membership->rolesToAdd()->pluck('role_id')->toArray();
        $position = [];
        for ($i = 1; $i <= 30; $i++) {
            $position[$i] = $i;
        }
        return view('base::admin.Membership.edit', compact('membership','roles', 'emails', 'pages', 'memRoles', 'memRolesRemove', 'memRolesAdd', 'position', 'memberPages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditMembershipRequest $request, $id)
    {
        $this->membershipService->updateMembership($request, $id);
        return redirect('/admin/membership');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->membershipService->delete($id))
        {
            return redirect('/admin/membership');
        } else {
            return redirect('/admin/membership/'.$id.'/edit');
        }
    }
}
