<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : MembershipService.php
 **/

namespace Quantum\base\Services;


use Carbon\Carbon;
use Event;
use Quantum\base\Events\GainedRole;
use Quantum\base\Events\LostRole;
use Quantum\base\Models\User;
use Quantum\base\Models\Transactions;
use Quantum\base\Models\UserPurchase;
use Quantum\base\Events\UserRegistered;
use Quantum\base\Events\UserUpgraded;
use Quantum\base\Models\MembershipTypes;
use Quantum\base\Models\UserMembership;
use Session;

class MembershipService
{

    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var CacheService
     */
    private $cacheService;

    public function __construct(EmailService $emailService, CacheService $cacheService)
    {
        $this->emailService = $emailService;
        $this->cacheService = $cacheService;
    }

    public function CreateMembership($request)
    {
        if($request->roles)
        {
            $roles = $request->roles;
            array_push($roles, 3);
        } else {
            $roles = ['3'];
        }
        $amount = isset($request->amount) ? $request->amount : 0;
        $membership = MembershipTypes::create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'description' => $request->description,
            'summary' => $request->summary,
            'type' => $request->type,
            'amount' => str_replace(',','' , $amount),
            'expires' => $request->expires,
            'subscription' => $request->subscription,
            'subscription_period_amount' => $request->subscription_period_amount,
            'subscription_period_type' => $request->subscription_period_type,
            'expired_remove_roles' => $request->expired_remove_roles,
            'expired_change_status' => $request->expired_change_status,
            'expired_change_status_to' => $request->expired_change_status_to,
            'emails_id' => $request->emails_id,
            'page_after_registration' => $request->page_after_registration,
            'members_page_after_payment' => $request->members_page_after_payment,
            'guest_page_after_payment' => $request->guest_page_after_payment,
            'allow_user_signups' => $request->allow_user_signups,
            'display_in_collections' => $request->display_in_collections,
            'position' => $request->position,
            'status' => $request->status,
            'register_default' => $request->register_default,
            'email_validate' => $request->email_validate,
            'login_after_register' => $request->login_after_register,
            'expire_email_id' => $request->expire_email_id
        ]);

        $this->toggleDefault($membership);

        $this->membershipSync($request, $membership);

        flash('Membership has been created.')->success();
        \Activitylogger::log('Admin - Created Membership : '.$membership->title, $membership);
        
        return $membership;
    }
    
    private function toggleDefault($membership)
    {
        if($membership->type == 'free')
        {
            if($membership->register_default == '1')
            {
                MembershipTypes::where('id', '!=', $membership->id)
                    ->where('register_default', '1')
                    ->tenant()
                    ->update(['register_default' => '0']);
            }
        }
    }

    private function membershipSync($request, $membership)
    {
        if($request->roles)
        {
            $roles = $request->roles;
            array_push($roles, 3);
        } else {
            $roles = ['3'];
        }

        $membership->Roles()->sync($roles);

        if($request->roles_to_remove)
        {
            $membership->RolesToRemove()->sync($request->roles_to_remove);
        }

        if($request->expired_add_roles)
        {
            $membership->RolesToAdd()->sync($request->expired_add_roles);
        }
    }

    public function updateMembership($request, $id)
    {
        $membership = MembershipTypes::where('id', $id)->tenant()->firstOrFail();

        if($request->roles)
        {
            $roles = $request->roles;
            array_push($roles, 3);
        } else {
            $roles = ['3'];
        }
        $amount = isset($request->amount) ? $request->amount : 0;
        $membership->title = $request->title;
        $membership->slug = str_slug($request->title);
        $membership->description = $request->description;
        $membership->summary = $request->summary;
        $membership->type = $request->type;
        $membership->amount = str_replace(',','' , $amount);
        $membership->expires = $request->expires;
        $membership->subscription = $request->subscription;
        $membership->subscription_period_amount = $request->subscription_period_amount;
        $membership->subscription_period_type = $request->subscription_period_type;
        $membership->expired_remove_roles = $request->expired_remove_roles;
        $membership->expired_change_status = $request->expired_change_status;
        $membership->expired_change_status_to = $request->expired_change_status_to;
        $membership->emails_id = $request->emails_id;
        $membership->page_after_registration = $request->page_after_registration;
        $membership->members_page_after_payment = $request->members_page_after_payment;
        $membership->guest_page_after_payment = $request->guest_page_after_payment;
        $membership->allow_user_signups = $request->allow_user_signups;
        $membership->display_in_collections = $request->display_in_collections;
        $membership->position = $request->position;
        $membership->status = $request->status;
        $membership->register_default = $request->register_default;
        $membership->email_validate = $request->email_validate;
        $membership->login_after_register = $request->login_after_register;
        $membership->expire_email_id = $request->expire_email_id;
        $membership->save();

        $this->toggleDefault($membership);

        $this->membershipSync($request, $membership);



        flash('Membership has been updated.')->success();
        \Activitylogger::log('Admin - Updated Membership : '.$membership->title, $membership);

        return $membership;
    }

    public function delete($id)
    {
        $membership = MembershipTypes::where('id', $id)->tenant()->firstOrFail();
        $count = $membership->Users()->count();
        if($count > 0)
        {
            flash('Membership has existing users.')->error();
            return false;
        } else {
            flash('Membership has been deleted.')->success();
            \Activitylogger::log('Admin - Deleted Membership : '.$membership->title, $membership);
            $membership->delete();
            return true;
        }
    }

    public function addUserMembership($membershipId, $user, $flash = true, $purchaseID = null)
    {
        if(!is_object($user))
        {
            $user = User::where('id', $user)->firstOrFail();
        }

      //  if($user->id == '1') return false;

        if(!is_object($membershipId))
        {
            $membership = MembershipTypes::where('id', $membershipId)->tenant()->firstOrFail();
        } else {
            $membership = $membershipId;
        }
        
        //duplicate check
        $memcheck = UserMembership::where('user_id', $user->id)->where('membership_types_id', $membership->id)->where('status', 'active')->first();

        if($memcheck){
            if($flash) flash('Membership already exists and is active.')->error();
            return $memcheck;
        }

        $expireDate = $this->createExpireDate($membership);

        //create user-membership
        $userMembership = UserMembership::create([
            'user_id' => $user->id,
            'user_purchase_id' => $purchaseID,
            'membership_types_id' => $membership->id,
            'expires' => $membership->expires ? $membership->expires : 0,
            'expires_on' => $expireDate,
            'status' => 'active'
        ]);
        //remove roles
        foreach ($membership->rolesToRemove as $role)
        {
            $user->roles()->detach($role);
            Event::fire(new LostRole($user, $role->id));
        }
        //add roles
        foreach ($membership->roles as $role)
        {
            if(!$user->hasRole($role))
            {
                $user->roles()->attach($role);
                Event::fire(new GainedRole($user, $role->id));
            }
        }
        //send email
        if($membership->emails_id > 0)
        {
            $this->emailService->send_system_mail($user, $membership->emails_id);
        }
        //log
        if($flash) {
            flash('Membership has been Added.')->success();
            \Activitylogger::log('Added Membership : '.$membership->title.' to user '.$user->username, $userMembership);
        } else {
            \Activitylogger::log('Added Membership : '.$membership->title.' to user '.$user->username, $userMembership, $user);
        }
        $this->cacheService->clearUserCache($user);

        return $userMembership;

    }

    public function createExpireDate($membership)
    {
        $expireDate = Carbon::now();
        $expireDate->addHours(2);  //add 2 hours to allow for ipn failures
        if($membership->expires == 1)
        {
            switch ($membership->subscription_period_type)
            {
                case 'Days':
                    $expireDate->addDays($membership->subscription_period_amount);
                    break;
                case 'Weeks':
                    $expireDate->addWeeks($membership->subscription_period_amount);
                    break;
                case 'Months':
                    $expireDate->addMonths($membership->subscription_period_amount);
                    break;
                case 'Years':
                    $expireDate->addYears($membership->subscription_period_amount);
                    break;
            }
        }
        return $expireDate;
    }

    public function removeUserMembersip($membershipId, $user, $system = false)
    {
        if(!is_object($user))
        {
            $user = User::where('id', $user)->firstOrFail();
        }
     //   if($user->id == '1') return false;

        $userMembership = UserMembership::where('id', $membershipId)->where('user_id', $user->id)->firstOrfail();
        $membership = $userMembership->membership;

        //removed added roles
        foreach ($membership->roles as $role)
        {
            if($role->id != '3') {
                $user->roles()->detach($role);
                Event::fire(new LostRole($user, $role->id));
            }
        }

        //add roles
        foreach ($membership->rolesToAdd as $role)
        {
            if(!$user->hasRole($role))
            {
                $user->roles()->attach($role);
                Event::fire(new GainedRole($user, $role->id));
            }
        }
        //change status
        if($membership->expired_change_status)
        {
            $user->status = $membership->expired_change_status_to;
            $user->save();
        }

        //expire membership
        $userMembership->status = 'inactive';
        $userMembership->save();
        $this->cacheService->clearUserCache($user);
        //send email
        if($membership->expire_email_id && $membership->expire_email_id > 0)
        {
            $this->emailService->send_system_mail($user, $membership->expire_email_id);
        }
        //log
        flash('Membership has expired.')->success();
        $userExpired = ($system != false) ? $user : null;
        \Activitylogger::log('Membership Expired : '.$membership->title.' for user '.$user->username, $userMembership, $userExpired);
        return $userMembership;
    }

    public function membershipLinkClick($id)
    {
        //upgrade free-membership
        //register free-membership

    }

    public function showRegisterForm($membership = null)
    {
        //paid membership
        if (Session::has('user_purchase_id')) {
            if($userPurchase = UserPurchase::with('items')->where('id', Session::get('user_purchase_id'))->first())
            {
                foreach($userPurchase->items as $item)
                {
                    if($item->model == '\Quantum\base\Models\MembershipTypes')
                    {
                        Session::put('membership_type', $item->model_id);
                        return true;
                    }
                }
            }
            return false;
        }
        //free non default
        if ($membership != null) {
            $membership_type = MembershipTypes::where('type', 'free')->where('slug', $membership)->where('status', 'active')->where('allow_user_signups', '1')->tenant()->firstorFail();
            Session::put('membership_type', $membership_type->id);
            return true;
        }
        else { //free default
            $membership_type = MembershipTypes::where('type', 'free')->where('register_default', '1')->where('status', 'active')->where('allow_user_signups', '1')->tenant()->firstorFail();
            Session::put('membership_type', $membership_type->id);
            return true;
        }

    }
    
    public function postedRegisterForm($request)
    {
        if (Session::has('user_purchase_id')) {
            if($userPurchase = UserPurchase::with('items')->where('id', Session::get('user_purchase_id'))->first())
            {
                foreach($userPurchase->items as $item)
                {
                    if($item->model == '\Quantum\base\Models\MembershipTypes')
                    {
                        $membership_type = MembershipTypes::where('id', $item->model_id)->where('status', 'active')->where('allow_user_signups', '1')->tenant()->firstorFail();
                        $user = $this->createMember($request, $membership_type);
                        $userPurchase->user_id = $user->id;
                        $userPurchase->save();
                        Session::forget('user_purchase_id');
                        Session::forget('membership_type');
                        return $membership_type;
                    }
                }
            }

        } else {
            $membershipId = Session::get('membership_type');
            $membership_type = MembershipTypes::where('id', $membershipId)->where('type', 'free')->where('status', 'active')->where('allow_user_signups', '1')->tenant()->firstorFail();
            $this->createMember($request, $membership_type);
            Session::forget('membership_type');
            Session::put('membership_alt_url', '1');
            return $membership_type;
        }
    }
    
    private function createMember($request, $membership)
    {
        $request->merge([
            'password' => bcrypt($request->password),
            'email_confirmed' => ($membership->email_validate == '1') ? 'false' : 'true',
            'registered_at' => config('app.name')
        ]);
        $user = User::create($request->except('password_confirmation'));
        //$user->password = bcrypt($request->password);
        // if($membership->email_validate == '1') $user->email_confirmed = 'false';
        //$user->save();

        $user->profile()->create($request->all());
        
        $this->addUserMembership($membership, $user, false);

        $user->load('role'); //reload roles
        \Event::fire(new UserRegistered($user));

        if($membership->login_after_register == '1') \Auth::login($user);
        
        //Log, feedback and return
        \Activitylogger::log('Created Membership : '.$user->username, $user, $user);
        return $user;
    }

    public function upgradeNeeded($roleNeeded = null)
    {
        //no upgrade path to admin
        if($roleNeeded == 'admin') abort(404);

        if(!$this->isUpgradePath()) abort(404);
        //show upgrade page
        return \Redirect::to(\Settings::get('members_upgrade_page'));
    }

    public function isUpgradePath()
    {
        //get users membership
        $freeMember = false;
        $paidMember = false;
        foreach(\Auth::user()->membership as $membership)
        {
            if($membership->type == 'free'){
                $freeMember = true;
            }
            if($membership->type == 'paid'){
                $paidMember = true;
            }
        }
        //is there an upgrade path
        if($paidMember == true) return false;

        return true;
    }
    
    public function membershipPurchased($event)
    {
        if($userPurchase = UserPurchase::with('items')->where('id', $event->transaction->user_purchase_id)->first())
        {
            foreach($userPurchase->items as $item)
            {
                if($item->model == '\Quantum\base\Models\MembershipTypes')
                {
                    if($membership = $this->addUserMembership($item->model_id, $userPurchase->user_id, true, $userPurchase->id))
                    {
                        \Event::fire(new UserUpgraded($membership));
                        $userPurchase->status = 'active';
                        $userPurchase->save();
                    }
                }
            }
        }
    }


    public function checkExpired()
    {
        $date = Carbon::now();

        $expiredMembers = UserMembership::where('expires', '1')->where('expires_on', '<', $date)->where('status', 'active')->get();
        foreach ($expiredMembers as $expired)
        {
            if($this->hasActiveSubscription($expired)) continue;
            $this->removeUserMembersip($expired->id, $expired->user_id, true);
        }
    }

    private function hasActiveSubscription($expired)
    {
        if($expired->user_purchase_id && $expired->user_purchase_id > 0)
        {
            $subscription = Transactions::where('user_id', $expired->user_id)->where('user_purchase_id', $expired->user_purchase_id)->first();
            if(!$subscription) return false;
            if($subscription->status == 1) return true;
        }
        return false;
    }



}