<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : SubscriberService.php
 **/

namespace Quantum\newsletter\Services;

use Carbon\Carbon;
use Quantum\base\Models\User;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterImportQueue;
use Quantum\newsletter\Models\NewsletterMail;
use Quantum\newsletter\Models\NewsletterSubscriber;

class SubscriberService
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var NewsletterEmailService
     */
    private $newsletterEmailService;

    protected $newsletter;

    public function __construct(NewsletterService $newsletterService, NewsletterEmailService $newsletterEmailService)
    {
        $this->newsletterService = $newsletterService;
        $this->newsletterEmailService = $newsletterEmailService;
    }

    public function createSubscriber($request)
    {
        //get any subscriptions
        $currentSubscriber = NewsletterSubscriber::where('email', $request->email)->get();
        $user = User::where('email', $request->email)->first();
        $subscribed = 0;


        foreach ($request->newsletters as $newsletterSel)
        {
            $newsletter = $this->newsletterService->getNewsletterBySlug($newsletterSel, ['noJoin', 'roles', 'template']);

            //check if subscribed
            if($currentSubscriber)
            {
                if($currentSubscriber->contains('newsletter_id', $newsletter->id)) continue;
            }

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
            if(!$allowed) continue;

            //no join check
            if($newsletter->noJoin)
            {
                $allowed = true;
                foreach ($newsletter->noJoin as $noJoin)
                {
                    if($currentSubscriber)
                    {
                        if($currentSubscriber->contains('newsletter_id', $noJoin->id)) $allowed = false;
                    }
                }
                if(!$allowed) continue;
            }

            //set responder sequence
            $sequence = 0;
            $sequence_send_on = null;
            if($request->start_responder && $request->start_responder == '1')
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
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'email_confirmed' => 1,
                'sequence' => $sequence,
                'sequence_send_on' => $sequence_send_on,
                'newsletter_import_id' => null,
                'sub_code' => str_random(40)
            ]);
            $subscriber->user = $user;
            $subscriber->newsletter = $newsletter;

            //send welcome email
            if($request->send_welcome_email && $request->send_welcome_email == '1')
            {
                if($message = $newsletter->mails->where('message_type', 'welcome_email')->sortBy('position')->first())
                {
                    $this->newsletterEmailService->sendEmail($message, $subscriber);
                }
            }
            $subscribed++;
            \Activitylogger::log('Admin - Created Newsletter Subscriber : '.$request->email, $newsletter);
        }

        if($subscribed > 0)
        {
            flash('Subscriber added to '.$subscribed.' '.str_plural('Newsletters', $subscribed))->success();
        } else {
            flash('Subscriber not added to any newsletter')->error();
        }
    }


    public function getAllSubscribers()
    {
        $subscribers = NewsletterSubscriber::with('user', 'newsletter')->where('active', 1)->orderBy('last_name', 'DESC')->paginate(40);
        return $subscribers;
    }

    public function searchSubscribers($request)
    {
        $subscribers = NewsletterSubscriber::with('user', 'newsletter')
            ->like('first_name', $request->first_name)
            ->like('last_name', $request->last_name)
            ->like('email', '%'.$request->email.'%')
            ->search('newsletter_id', $request->newsletter)
            ->orderBy('last_name', 'DESC')->paginate(40);
        return $subscribers;
    }

    public function newsletterSubscribers($newsletter)
    {
        $newsletter = $this->newsletterService->getNewsletterBySlug($newsletter);
        $subscribers = NewsletterSubscriber::with('user', 'newsletter')
            ->search('newsletter_id', $newsletter->id)
            ->where('active', 1)
            ->orderBy('last_name', 'DESC')->paginate(40);
        return $subscribers;
    }

    public function getSubscriberById($id)
    {
        $subscriber = NewsletterSubscriber::with('user', 'newsletter')->where('id', $id)->first();
        $subscriber->otherNewsletter = NewsletterSubscriber::with('newsletter')->where('id', '!=', $subscriber->id)->where('email', $subscriber->email)->get();
        return $subscriber;
    }

    public function getSubscriberByEmail($request)
    {
        $subscriber = NewsletterSubscriber::with('user', 'newsletter')->where('email', $request->email)->first();
        if($subscriber) $subscriber->otherNewsletter = NewsletterSubscriber::with('newsletter')->where('id', '!=', $subscriber->id)->where('email', $subscriber->email)->get();
        return $subscriber;
    }

    public function updateSubscriber($request, $id)
    {
        $moved = false;
        $subscriber = $this->getSubscriberById($id);
        $subscriber->otherNewsletter = NewsletterSubscriber::with('newsletter')->where('id', '!=', $subscriber->id)->where('email', $subscriber->email)->get();
        //moving
        if($request->moveToNewsletter > 0)
        {
            $newsletter = $this->newsletterService->getNewsletterById($request->moveToNewsletter, ['noJoin', 'roles', 'template']);


            //check if subscribed
            if($subscriber->otherNewsletter && $subscriber->otherNewsletter->count() > 0)
            {
                if($subscriber->otherNewsletter->contains('newsletter_id', $newsletter->id))
                {
                    flash('Subscriber is already subscriber to selected newsletter')->error();
                    return false;
                }
            }

            //check if has roles
            $allowed = false;
            if($newsletter->roles)
            {
                foreach ($newsletter->roles as $role)
                {
                    if($role->name == 'guest' && !$subscriber->user) $allowed = true;
                    if($subscriber->user && $subscriber->user->hasRole($role->name)) $allowed = true;
                }
            }
            if(!$allowed)
            {
                flash('Subscriber does not have required role to join selected newsletter.')->error();
                return false;
            }
            //no join check
            if($newsletter->noJoin)
            {
                $allowed = true;
                foreach ($newsletter->noJoin as $noJoin)
                {
                    if($subscriber->otherNewsletter && $subscriber->otherNewsletter->count() > 0)
                    {
                        if($subscriber->otherNewsletter->contains('newsletter_id', $noJoin->id)) $allowed = false;
                    }
                }
                if(!$allowed)
                {
                    flash('Subscriber can not join selected newsletter.')->error();
                    return false;
                }
            }

            //set responder sequence
            $sequence = 0;
            $sequence_send_on = null;
            if($request->start_responder && $request->start_responder == '1')
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
            $subscriber->newsletter_id = $newsletter->id;
            $subscriber->sequence = $sequence;
            $subscriber->sequence_send_on = $sequence_send_on;
            unset($subscriber->otherNewsletter);
            $subscriber->save();
            $moved = true;
        }
        //delete
        if(!$moved)
        {
            if($request->removeFromNewsletter && $request->removeFromNewsletter == '1')
            {
                $subscriber->delete();
                flash('Subscriber has been deleted.')->success();
                return true;
            }
        }
        //update details
        if(isset($request->first_name)) $subscriber->first_name = $request->first_name;
        if(isset($request->last_name)) $subscriber->last_name = $request->last_name;
        if(isset($request->email)) $subscriber->email = $request->email;
        unset($subscriber->otherNewsletter);
        $subscriber->save();
        //remove other
        $allNewsletters = [];
        array_push($allNewsletters, $subscriber->newsletter_id);
        if($request->otherNewsletters && count($request->otherNewsletters) > 0)
        {
            foreach ($request->otherNewsletters as $othNews)
            {
                array_push($allNewsletters, (int)$othNews);
            }
        }
        NewsletterSubscriber::where('email', $subscriber->email)->whereNotIn('newsletter_id', $allNewsletters)->delete();


        flash('Subscriber has been updated.')->success();
        return true;
    }

    public function userSubscribe($request, $id)
    {
        $data = [];
        $newsletter = $this->newsletterService->getNewsletterBySlug($id, ['noJoin', 'roles', 'template']);
        //get any subscriptions
        $currentSubscriber = NewsletterSubscriber::where('email', $request->email)->get();

        //check if subscribed
        if($currentSubscriber)
        {
            if($currentSubscriber->contains('newsletter_id', $newsletter->id))
            {
                $data['success'] = false;
                $data['errorMsg'] = 'You are already subscribed to this newsletter.';
                $data['successHtml'] = 'You are already subscribed to this newsletter.';
                return $data;
            }
        }

        $user = User::where('email', $request->email)->first();

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
        if(!$allowed)
        {
            $data['success'] = false;
            $data['errorMsg'] = 'You can not subscribed to this newsletter at this time.';
            $data['successHtml'] = 'You can not subscribed to this newsletter at this time.';
            return $data;
        }
        //no join check
        if($newsletter->noJoin)
        {
            $allowed = true;
            foreach ($newsletter->noJoin as $noJoin)
            {
                if($currentSubscriber)
                {
                    if($currentSubscriber->contains('newsletter_id', $noJoin->id)) $allowed = false;
                }
            }
            if(!$allowed)
            {
                $data['success'] = false;
                $data['errorMsg'] = 'You can not subscribed to this newsletter at this time.';
                $data['successHtml'] = 'You can not subscribed to this newsletter at this time.';
                return $data;
            }
        }


        //subscribe
//set responder sequence
        $sequence = 0;
        $sequence_send_on = null;

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

        $subName = null;
        if(isset($request->first_name)) $subName = $request->first_name;
        if(isset($request->name)) $subName = $request->name;
        //verify email
        if(!$user && $newsletter->confirm_non_member == 1)
        {
            $subscriber = NewsletterSubscriber::create([
                'newsletter_id' => $newsletter->id,
                'user_id' => isset($user) ? $user->id : null,
                'first_name' => $subName,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'email_confirmed' => 0,
                'sequence' => 0,
                'sequence_send_on' => null,
                'newsletter_import_id' => null,
                'sub_code' => str_random(40)
            ]);
            $subscriber->newslettter = $newsletter;
            if($message = $newsletter->mails->where('message_type', 'confirmation_email')->sortBy('position')->first())
            {
                $this->newsletterEmailService->sendEmail($message, $subscriber);
            }


        } else {
            $subscriber = NewsletterSubscriber::create([
                'newsletter_id' => $newsletter->id,
                'user_id' => isset($user) ? $user->id : null,
                'first_name' => $subName,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'email_confirmed' => 1,
                'sequence' => $sequence,
                'sequence_send_on' => $sequence_send_on,
                'newsletter_import_id' => null,
                'sub_code' => str_random(40)
            ]);
            $subscriber->newslettter = $newsletter;
            if($message = $newsletter->mails->where('message_type', 'welcome_email')->sortBy('position')->first())
            {
                $this->newsletterEmailService->sendEmail($message, $subscriber);
            }
        }

        if($user) \Activitylogger::log('New Newsletter Subscriber : '.$request->email.' -- Newsletter : '.$newsletter->title, $subscriber, $user);

        $successPage = $newsletter->pages->where('page_type', 'subscribed_page')->first();
        $data['success'] = true;
        $data['successHtml'] = $successPage->content;
        $data['page'] = $successPage;
        return $data;
    }

    public function confirmEmail($sub_code)
    {
        $showPage = false;
        $subscribers = NewsletterSubscriber::with(['newsletter' => function($query) {
            $query->with(['mails', 'pages', 'template']);
        }])->where('sub_code', $sub_code)->get();

        if(!$subscribers) abort(404);

        foreach ($subscribers as $subscriber)
        {
            //set responder sequence
            $sequence = 0;
            $sequence_send_on = null;

            if($responder = $subscriber->newsletter->mails->where('message_type', 'responder')->sortBy('position')->first())
            {
                $sequence = 1;
                if($responder->interval_type == 'Minutes') $sequence_send_on = Carbon::now()->addMinutes($responder->interval_amount);
                if($responder->interval_type == 'Hours') $sequence_send_on = Carbon::now()->addHours($responder->interval_amount);
                if($responder->interval_type == 'Days') $sequence_send_on = Carbon::now()->addDays($responder->interval_amount);
                if($responder->interval_type == 'Weeks') $sequence_send_on = Carbon::now()->addWeeks($responder->interval_amount);
                if($responder->interval_type == 'Months') $sequence_send_on = Carbon::now()->addMonths($responder->interval_amount);
                if($responder->interval_type == 'Years') $sequence_send_on = Carbon::now()->addYears($responder->interval_amount);
            }

            $subscriber->email_confirmed = 1;
            $subscriber->sequence = $sequence;
            $subscriber->sequence_send_on = $sequence_send_on;
            $subscriber->save();
            $user = User::where('id', 1)->first();

            //send welcome
            if($message = $subscriber->newsletter->mails->where('message_type', 'welcome_email')->sortBy('position')->first())
            {
                $this->newsletterEmailService->sendEmail($message, $subscriber);
            }

            \Activitylogger::log('Newsletter Subscriber Confirmed Email : '.$subscriber->email, $subscriber, $user);

            $showPage = $subscriber->newsletter->pages->where('page_type', 'confirmed_email')->first();

        }

        return $showPage;
    }

    public function unsubscribe($news_code, $sub_code)
    {
        $newsletter = Newsletter::with('pages')->where('news_code', $news_code)->firstOrFail();
        $subscriber = NewsletterSubscriber::where('newsletter_id', $newsletter->id)->where('sub_code', $sub_code)->firstOrFail();
        $subscriber->delete();
        $showPage = $newsletter->pages->where('page_type', 'unsubscribed_page')->first();
        return $showPage;
    }

    public function unsubscribeMember($id)
    {
        $newsletter = Newsletter::with('pages')->where('slug', $id)->firstOrFail();
        $subscriber = NewsletterSubscriber::where('newsletter_id', $newsletter->id)->where('email', \Auth::user()->email)->firstOrFail();
        $subscriber->delete();
        $showPage = $newsletter->pages->where('page_type', 'unsubscribed_page')->first();
        return $showPage;
    }

    public function unsubscribeUser($id, $user)
    {
        $newsletter = Newsletter::with('pages')->where('slug', $id)->firstOrFail();
        $subscriber = NewsletterSubscriber::where('newsletter_id', $newsletter->id)->where('email', $user->email)->firstOrFail();
        $subscriber->delete();
        $showPage = $newsletter->pages->where('page_type', 'unsubscribed_page')->first();
        return $showPage;
    }

}