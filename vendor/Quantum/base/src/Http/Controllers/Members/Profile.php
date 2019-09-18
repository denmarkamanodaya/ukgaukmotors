<?php

namespace Quantum\base\Http\Controllers\Members;

use Illuminate\Support\Facades\Auth;
use Quantum\base\Models\Countries;
use Quantum\base\Services\UserService;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\UserMembership;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterSubscriber;
use Quantum\base\Models\NotificationTypes;

class Profile extends Controller
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
    public function index()
    {
        $user = Auth::user();
        $user->load('roles');
        $roles = $user->roles->pluck('id')->toArray();
        $userMembership = UserMembership::with('subscription')->where('user_id', Auth::user()->id)->where('status', 'active')->get();
        $notifications = NotificationTypes::where('allow_members', '1')->get();
        $newsSubscriptions = NewsletterSubscriber::with('newsletter')->where('user_id', Auth::user()->id)->get();
        $newsletters = Newsletter::whereHas('roles', function($query) use($roles){
            $query->whereIn('id', $roles);
        })->where('visible_in_lists', 1)->where('allow_subscribers', 1)->get();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('base::members.Profile.show', compact('userMembership', 'notifications', 'newsSubscriptions', 'newsletters', 'countries'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\Quantum\base\Http\Requests\Members\UpdateProfileRequest $request)
    {
        $this->userService->updateMemberProfile($request);
        return redirect('members/profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return $this->userService->deleteAccount();
    }
}
