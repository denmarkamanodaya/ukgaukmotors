<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Http\Requests\Admin\AddUserMembershipRequest;
use Quantum\base\Http\Requests\Admin\RemoveUserMembershipRequest;
use Quantum\base\Http\Requests\Admin\UserSearchRequest;
use Quantum\base\Models\Countries;
use Quantum\base\Models\Role;
use Quantum\base\Models\User;
use Quantum\base\Services\UserService;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\MembershipTypes;
use Quantum\base\Models\UserMembership;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterSubscriber;
use Quantum\base\Models\NotificationTypes;

class Users extends Controller
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($role = false)
    {
        $searchrole = $role ? $role : 'member';

        $searchrole = Role::whereName($searchrole)->firstOrFail();

        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->pluck('title', 'name');

        $users = User::whereHas('role', function($q) use ($searchrole)
        {
            $q->where('id', $searchrole->id);
        })->paginate(20);
        return view('base::admin.Users.index', compact('roles', 'searchrole', 'users'));
    }

    public function showRole(\Quantum\base\Http\Requests\Admin\ChangeRoleRequest $changeRoleRequest)
    {
        $role = $changeRoleRequest->user_role;
        return $this->index($role);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = \Quantum\base\Models\Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->where('name', '!=', 'member')->get();
        $memberships = MembershipTypes::where('status', 'active')->orderBy('position', 'ASC')->tenant()->pluck('title','id')->toArray();

        $membership = ['0' => 'Do Not add a Membership' ];
        $memberships = $membership + $memberships;
        $notifications = NotificationTypes::where('allow_members', '1')->get();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('base::admin.Users.create', compact('roles', 'memberships', 'notifications', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Quantum\base\Http\Requests\Admin\CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request);
        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username)
    {
        $user = User::with('roles','membership', 'notifications')->where('username', $username)->firstOrFail();
        $roles = \Quantum\base\Models\Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->where('name', '!=', 'member')->get();
        $memberships = MembershipTypes::where('status', 'active')->orderBy('position', 'ASC')->tenant()->pluck('title','id')->toArray();
        $userMembership = UserMembership::where('user_id', $user->id)->where('status', 'active')->get();
        $notifications = NotificationTypes::where('allow_members', '1')->get();
        $newsSubscriptions = NewsletterSubscriber::with('newsletter')->where('user_id', $user->id)->get();
        $newsletters = Newsletter::where('visible_in_lists', 1)->where('allow_subscribers', 1)->get();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('base::admin.Users.edit', compact('user', 'roles', 'memberships', 'userMembership', 'notifications', 'newsSubscriptions', 'newsletters', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\Quantum\base\Http\Requests\Admin\EditUserRequest $request, $id)
    {
        $user = $this->userService->updateUser($id,$request);
        return redirect('/admin/user/'.$user->username.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userService->removeUser($id);
        return redirect('/admin/users');
    }

    public function removeProfilePicture($id)
    {
        $user = $this->userService->removeProfilePicture($id);
        return redirect('/admin/user/'.$user->username.'/edit');
    }

    public function addMembership(AddUserMembershipRequest $request, $id)
    {
        $user = $this->userService->addMembership($request, $id);
        return redirect('/admin/user/'.$user->username.'/edit');
    }
    
    public function removeMembership(RemoveUserMembershipRequest $request, $id)
    {
        $user = $this->userService->removeMembership($request, $id);
        return redirect('/admin/user/'.$user->username.'/edit');
    }

    public function search(UserSearchRequest $request)
    {
        $search = $this->userService->search($request);
        return view('base::admin.Users.search', compact('search'));
    }

    public function loginAs($id)
    {
        if(\Auth::user()->id != 1) return redirect('/admin/dashboard');
        \Auth::loginUsingId($id);
        return redirect('/members/dashboard');
    }
}
