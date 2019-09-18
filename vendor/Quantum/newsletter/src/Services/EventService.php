<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : EventService.php
 **/

namespace Quantum\newsletter\Services;


use Carbon\Carbon;
use Log;
use Quantum\base\Models\Role;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterSubscriber;

class EventService
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var NewsletterEmailService
     */
    private $newsletterEmailService;

    public function __construct(NewsletterService $newsletterService, NewsletterEmailService $newsletterEmailService)
    {
        $this->newsletterService = $newsletterService;
        $this->newsletterEmailService = $newsletterEmailService;
    }

    public function ProfileUpdated($event)
    {
        NewsletterSubscriber::where('user_id', $event->user->id)->update([
            'first_name' => $event->user->profile->first_name,
            'last_name' => $event->user->profile->last_name,
            'email' => $event->user->email,
        ]);
    }

    public function UserCreated($event)
    {
        NewsletterSubscriber::where('email', $event->user->email)->update([
            'first_name' => $event->user->profile->first_name,
            'last_name' => $event->user->profile->last_name,
            'email' => $event->user->email,
            'user_id' => $event->user->id,
        ]);
        $subscriptions = NewsletterSubscriber::where('user_id', $event->user->id)->get();
        $newsletters = Newsletter::with('roles', 'noJoin', 'roleMove', 'mails')->where('autojoin_register', 1)->get();

        if($newsletters)
        {
            foreach ($newsletters as $newsletter)
            {
                //multisite ?
                if(count(config('main.multisite_sites')) > 0) {

                    //check and convert to array selected sites
                    if(!is_null($newsletter->multisite_sites)) {
                        $newsletter->multisite_sites = unserialize($newsletter->multisite_sites);
                    } else {
                        continue;
                    }
                    //check if selected site matches current site
                    if(in_array(config('app.name'), $newsletter->multisite_site)) $this->joinNewsletter($newsletter, $event->user, $subscriptions);
                } else {
                    //normal site so signup
                    $this->joinNewsletter($newsletter, $event->user, $subscriptions);
                }

            }
        }
    }

    public function UserDeleted($event)
    {

        //cycle through all roles and run role change event (lose) but (gain) for guest role
        $roles = Role::whereNotIn('name',['super_admin'])->orderBy('id', 'DESC')->get();
        foreach ($roles as $role)
        {
            $event->role = $role->id;
            if($role->name != 'guest') {
                $this->RoleChange($event, 'lose');
            } else {
                $this->RoleChange($event, 'gain');
            }
        }
        //remove user link to any subscriptions
        NewsletterSubscriber::where('user_id', $event->user->id)->update([
            'user_id' => null,
        ]);
    }

    public function RoleChange($event, $type='gain')
    {
        //get all user subscriptions with newsletter
        if($subscriptions = NewsletterSubscriber::with(['user','newsletter' => function($query){
            $query->with('roles', 'noJoin', 'roleMove');
        }])->where('user_id', $event->user->id)->get())
        {
            foreach ($subscriptions as $subscription)
            {
                //search newsletter for gained role event
                if($subscription->newsletter->roleMove && $subscription->newsletter->roleMove->count() > 0)
                {
                    foreach ($subscription->newsletter->roleMove as $roleMove)
                    {
                        if($roleMove->role_action == $type && $roleMove->role_id == $event->role)
                        {
                            //move to newsletter
                            $this->moveToNewsletter($subscription, $roleMove, $subscriptions);
                        }
                    }
                }
            }
        }

    }

    private function moveToNewsletter($subscription, $roleMove, $subscriptions)
    {
        $newsletter = $this->newsletterService->getNewsletterById($roleMove->newsletter_new_id, ['noJoin', 'roles']);
        //check if subscribed
        if($subscriptions->contains('newsletter_id', $newsletter->id)) return false;
        //check if has roles
        $allowed = false;
        if($newsletter->roles)
        {
            foreach ($newsletter->roles as $role)
            {
                if($role->name == 'guest' && !$subscription->user) $allowed = true;
                if($subscription->user && $subscription->user->hasRole($role->name)) $allowed = true;
            }
        }
        if(!$allowed) return false;
        //no join check
        if($newsletter->noJoin)
        {
            $allowed = true;
            foreach ($newsletter->noJoin as $noJoin)
            {
                    if($subscriptions->contains('newsletter_id', $noJoin->id)) $allowed = false;
            }
            if(!$allowed) return false;
        }

        //set responder sequence
        $sequence = 0;
        $sequence_send_on = null;
        if($roleMove->start_responder && $roleMove->start_responder == '1')
        {
            if($responder = $newsletter->mails->where('message_type', 'responder')->sortBy('position')->first())
            {
                $sequence = 1;
                if($responder->interval_type == 'Minutes') $sequence_send_on = Carbon::now()->addMinutes($responder->interval_amount);
                if($responder->interval_type == 'Hours') $sequence_send_on = Carbon::now()->addHours($responder->interval_amount);
                if($responder->interval_type == 'Days') $sequence_send_on = Carbon::now()->addDays($responder->interval_amount);
                if($responder->interval_type == 'Weeks') $sequence_send_on = Carbon::now()->addWeeks($responder->interval_amount);
                if($responder->interval_type == 'Months') $sequence_send_on = Carbon::now()->addMonths($responder->interval_amount);
                if($responder->interval_type == 'Years') $sequence_send_on = Carbon::now()->addYears($responder->interval_amount);
            }
        }
        //update subscription
        $subscription->newsletter_id = $newsletter->id;
        $subscription->sequence = $sequence;
        $subscription->sequence_send_on = $sequence_send_on;
        $subscription->save();
        return true;
    }

    private function joinNewsletter($newsletter, $user, $subscriptions)
    {
        //check if subscribed
        if($subscriptions->contains('newsletter_id', $newsletter->id)) return false;
        //check if has roles
        $allowed = false;
        if($newsletter->roles)
        {
            foreach ($newsletter->roles as $role)
            {
                if($role->name == 'guest' && !$user) $allowed = true;
                if($user && $user->hasRole($role->name)) $allowed = true;
            }
        }
        if(!$allowed) return false;
        //no join check
        if($newsletter->noJoin)
        {
            $allowed = true;
            foreach ($newsletter->noJoin as $noJoin)
            {
                if($subscriptions->contains('newsletter_id', $noJoin->id)) $allowed = false;
            }
            if(!$allowed) return false;
        }

        //set responder sequence
        $sequence = 0;
        $sequence_send_on = null;
        if($newsletter->autojoin_start_responder == 1)
        {
            if($responder = $newsletter->mails->where('message_type', 'responder')->sortBy('position')->first())
            {
                $sequence = 1;
                if($responder->interval_type == 'Minutes') $sequence_send_on = Carbon::now()->addMinutes($responder->interval_amount);
                if($responder->interval_type == 'Hours') $sequence_send_on = Carbon::now()->addHours($responder->interval_amount);
                if($responder->interval_type == 'Days') $sequence_send_on = Carbon::now()->addDays($responder->interval_amount);
                if($responder->interval_type == 'Weeks') $sequence_send_on = Carbon::now()->addWeeks($responder->interval_amount);
                if($responder->interval_type == 'Months') $sequence_send_on = Carbon::now()->addMonths($responder->interval_amount);
                if($responder->interval_type == 'Years') $sequence_send_on = Carbon::now()->addYears($responder->interval_amount);
            }
        }

        //signup
        $subscriber = NewsletterSubscriber::create([
            'newsletter_id' => $newsletter->id,
            'user_id' => isset($user) ? $user->id : null,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'email' => $user->email,
            'email_confirmed' => 1,
            'sequence' => $sequence,
            'sequence_send_on' => $sequence_send_on,
            'newsletter_import_id' => null,
            'sub_code' => str_random(40)
        ]);

        //send welcome email
        if($newsletter->autojoin_send_welcome_email == 1)
        {
            if($message = $newsletter->mails->where('message_type', 'welcome_email')->sortBy('position')->first())
            {
                $this->newsletterEmailService->sendEmail($message, $subscriber);
            }
        }
    }
}