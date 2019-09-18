<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserService.php
 **/

namespace Quantum\base\Services;


use Event;
use Quantum\base\Models\ActivityLog;
use Quantum\base\Events\GainedRole;
use Quantum\base\Events\LostRole;
use Quantum\base\Events\ProfileUpdated;
use Quantum\base\Events\UserCreated;
use Quantum\base\Events\UserDeleted;
use Quantum\base\Models\Profile;
use Quantum\base\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Quantum\base\Models\Transactions;
use Quantum\base\Services\MembershipService;
use Quantum\base\Models\NotificationTypes;
use Quantum\base\Models\UserNotifications;

class UserService
{

    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var MembershipService
     */
    private $membershipService;

    public function __construct(EmailService $emailService, MembershipService $membershipService)
    {
        $this->emailService = $emailService;
        $this->membershipService = $membershipService;
    }

    private function superAdminCheck($id)
    {
        if ($id == 1)
        {
            if (\Auth::user()->id != $id)
            {
                flash('Only the master admin can edit this account.')->error();
                return redirect('/admin/users');
            }
        }
    }

    public function updateUser($id, $request)
    {
        $this->superAdminCheck($id);

        //get user
        $user = User::with('profile', 'roles')->find($id);

        //Profile picture
        $profilePicture = $this->profilePicture($user, $request);

        //do updates
        $user->username = $request['username'];
        $user->email = $request['email'];
        if($request['password'] != '')
        {
            $user->password = bcrypt($request['password']);
        }
        $user->status = $request['status'];
        $user->save();
        $user->profile->first_name = $request['first_name'];
        $user->profile->last_name = $request['last_name'];
        $user->profile->address = $request['address'];
        $user->profile->address2 = $request['address2'];
        $user->profile->city = $request['city'];
        $user->profile->county = $request['county'];
        $user->profile->postcode = $request['postcode'];
        $user->profile->country_id = $request['country_id'];
        $user->profile->telephone = $request['telephone'];
        $user->profile->bio = strip_tags($request['bio']);
        $user->profile->picture = $profilePicture;
        $user->profile->save();

        $this->syncRoles($request, $user);

        if(count($request->notification) > 0) {
            foreach ($request->notification as $notification => $setting) {
                if($setting == '' || is_null($setting)) continue;
                $notif = NotificationTypes::where('slug', $notification)->where('allow_members', '1')->first();
                if ($notif) {
                    $userNotif = UserNotifications::where('notification_types_id', $notif->id)->where('user_id',
                        $user->id)->first();
                    if ($userNotif) {
                        $userNotif->setting = $setting;
                        $userNotif->save();
                    } else {
                        UserNotifications::create([
                            'user_id'               => $user->id,
                            'notification_types_id' => $notif->id,
                            'setting'               => $setting
                        ]);
                    }
                }
            }
        }


        //Log, feedback and return
        flash('User has been updated.')->success();
        \Activitylogger::log('Admin - Updated User Profile : '.$user->username, $user);
        Event::fire(new ProfileUpdated($user));
        return $user;
    }

    private function syncRoles($request, $user)
    {
        $currrenRoles = [];
        $gainedRoles = [];
        //get current roles
        foreach($user->roles as $curRole)
        {
            array_push($currrenRoles, $curRole->id);
        }

        //sync roles but ensure role 3 is always included
        $roles = [0 => 3];
        if($request['roles'])
        {
            foreach ($request['roles'] as $key => $role)
            {
                if($role != '3') array_push($roles, $role);
                if(!in_array($role, $currrenRoles)) array_push($gainedRoles, $role);
            }
        }

        $user->roles()->sync($roles);
        if ($user->id == 1)
        {
            $user->roles()->attach(1);
        }

        $lostRoles= array_diff($currrenRoles, $roles);
        $this->BroadcastRoles($gainedRoles, $user);
        $this->BroadcastRoles($lostRoles, $user, 'lost');
    }

    private function BroadcastRoles($roles, $user, $type='gained')
    {
        if(!is_array($roles) || count($roles) == 0) return;

        if($type == 'gained')
        {
            foreach ($roles as $key => $role)
            {
                Event::fire(new GainedRole($user, $role));
            }
        }

        if($type == 'lost')
        {
            foreach ($roles as $key => $role)
            {
                Event::fire(new LostRole($user, $role));
            }
        }



    }


    private function profilePicture($user, $request)
    {

        $profilePic = isset($user->profile->picture)? $user->profile->picture : '';
        $path = user_photo_path($user->username);

        if($request['delPicture'])
        {
            $this->deleteProfileImages($path, $user);
            $profilePic = '';
        }

        if($request->file('profilePic'))
        {

            $profilePic = $request->file('profilePic')->getClientOriginalName();

            $image = Image::make($request->file('profilePic')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            if($profilePic != '')
            {
                $this->deleteProfileImages($path, $user);
            }

            //Save new
            $image->save($path. $profilePic);

        }
        if(starts_with($profilePic, 'http')) return $profilePic;
        return user_photo_url($user).$profilePic;
    }

    private function deleteProfileImages($path, $user)
    {
        $image = str_replace(user_photo_url($user), '', $user->profile->picture);
        if(starts_with($image, 'http')) return; //on other site in multi setup
        //remove old images
        File::delete($path . $image);
    }

    public function createUser($request)
    {
        $request->request->add(['registered_at' => config('app.name')]);
        $request_array = $request->except('password','password_confirmation');
        $request_array['password'] = bcrypt($request->password);
        $request_array['email_confirmed'] = true;
        $user = User::create($request_array);

        $user->profile()->create($request->all());

        $profilePicture = $this->profilePicture($user, $request);
        $user->profile->picture = $profilePicture;
        $user->profile->save();
        $this->syncRoles($request, $user);

        if($request->memberships != '0')
        {
            $this->membershipService->addUserMembership($request->memberships, $user);
        }

        if($request['send_welcome_email'] && $request['send_welcome_email'] == 1)
        {
            $this->emailService->send_system_mail($user, 'Welcome Email', $request);
        }

        //Log, feedback and return
        flash('User has been Created.')->success();
        \Activitylogger::log('Admin - Created User : '.$user->username, $user);
        Event::fire(new UserCreated($user));
        return $user;
    }

    public function removeProfilePicture($id)
    {
        $this->superAdminCheck($id);

        $user = User::with('profile')->findOrFail($id);

        $path = user_photo_path($user->username);
        $this->deleteProfileImages($path, $user);

        $user->profile->picture = '';
        $user->profile->save();

        flash('User profile picture has been removed.')->success();
        return $user;
    }

    public function removeUser($id, $userAction=false)
    {
        if($id == 1)
        {
            flash('This account can not be deleted.')->error();
            return false;
        }

        $user = User::with('profile')->findOrFail($id);
        $path = user_photo_path($user->username);
        File::deleteDirectory($path);
        
        ActivityLog::where('user_id', $user->id)->update(['user_id' => '1']);
        
        $user->membership()->delete();
        foreach($user->purchase as $purchase)
        {
            Transactions::where('user_purchase_id', $purchase->id)->update(['user_purchase_id' => '0']);
        }
        $user->purchase()->delete();
        $user->delete();
        if($userAction)
        {
            \Activitylogger::log('User Deleted Account : '.$user->username, $user);
            flash('Your account has been deleted.')->success();
            return $user;
        }
        \Activitylogger::log('Admin - Deleted User : '.$user->username, $user);
        flash('User has been removed.')->success();
        Event::fire(new UserDeleted($user));
        return $user;
    }

    public function updateMemberProfile($request)
    {
        //get user
        $user = User::with('profile')->findOrFail(Auth::user()->id);

        //Profile picture
        $profilePicture = $this->profilePicture($user, $request);

        //do updates
        $user->email = $request['email'];
        if($request['password'] != '')
        {
            $user->password = bcrypt($request['password']);
        }
        $user->save();

        $user->profile->first_name = $request['first_name'];
        $user->profile->last_name = $request['last_name'];
        $user->profile->address = $request['address'];
        $user->profile->address2 = $request['address2'];
        $user->profile->city = $request['city'];
        $user->profile->county = $request['county'];
        $user->profile->postcode = $request['postcode'];
        $user->profile->country_id = $request['country_id'];
        $user->profile->telephone = $request['telephone'];
        $user->profile->bio = strip_tags($request['bio']);
        $user->profile->picture = $profilePicture;
        $user->profile->save();

        if(count($request->notification) > 0) {
            foreach ($request->notification as $notification => $setting) {
                $notif = NotificationTypes::where('slug', $notification)->where('allow_members', '1')->first();
                if ($notif) {
                    $userNotif = UserNotifications::where('notification_types_id', $notif->id)->where('user_id',
                        $user->id)->first();
                    if ($userNotif) {
                        $userNotif->setting = $setting;
                        $userNotif->save();
                    } else {
                        UserNotifications::create([
                            'user_id'               => $user->id,
                            'notification_types_id' => $notif->id,
                            'setting'               => $setting
                        ]);
                    }
                }
            }
        }
        

        //Log, feedback and return
        flash('Profile has been updated.')->success();
        \Activitylogger::log('Updated Profile', $user, Auth::user());
        Event::fire(new ProfileUpdated($user));
        return $user;
    }
    
    public function addMembership($request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $this->membershipService->addUserMembership($request['memberships'], $id);
        return $user;
    }
    
    public function removeMembership($request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $this->membershipService->removeUserMembersip($request['membershipId'], $user);
        return $user;
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        $this->removeUser($user->id);
        $deletePage = Settings::get('members_deleted_account_page');
        Auth::logout();
        \Session::flush();
        Event::fire(new UserDeleted($user));
        return redirect(url($deletePage));
    }

    public function search($request)
    {
        $search = [];

        if($request->first_name && $request->first_name != '' && $request->last_name && $request->last_name != '')
        {
            $search['type'] = 'first_name';
            $search['users'] = Profile::with('user')->where('first_name', 'like', '%'.$request->first_name.'%')->where('last_name', 'like', '%'.$request->last_name.'%')->get();
            return $search;
        }

        if($request->first_name && $request->first_name != '')
        {
            $search['type'] = 'first_name';
            $search['users'] = Profile::with('user')->where('first_name', 'like', '%'.$request->first_name.'%')->get();
            return $search;
        }
        if($request->last_name && $request->last_name != '')
        {
            $search['type'] = 'last_name';
            $search['users'] = Profile::with('user')->where('last_name', 'like', '%'.$request->last_name.'%')->get();
            return $search;
        }
        if($request->email && $request->email != '')
        {
            $search['type'] = 'email';
            $search['users'] = User::where('email', $request->email)->get();
            return $search;
        }
        if($request->transactionid && $request->transactionid != '')
        {
            $search['type'] = 'transactionid';
            $search['users'] = Transactions::with('user')->where('trx_id', $request->transactionid)->get();
            return $search;
        }
        return false;
    }

}