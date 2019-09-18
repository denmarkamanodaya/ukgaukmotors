<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : NewsletterService.php
 **/

namespace Quantum\newsletter\Services;


use Carbon\Carbon;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterMail;
use Quantum\newsletter\Models\NewsletterPage;
use Quantum\newsletter\Models\NewsletterRoleMove;
use Quantum\newsletter\Models\NewsletterSubscriber;

class NewsletterService
{

    public function getAllNewsletters()
    {
        $newsletters = Newsletter::withCount(['subscribersActive', 'responders'])->orderBY('id', 'ASC')->paginate(20);
        return $newsletters;
    }

    public function getAllNewslettersList($except=null)
    {
        if($except)
        {
            $newsletters = Newsletter::where('id', '!=', $except)->orderBY('id', 'ASC')->pluck('title', 'id');
        } else {
            $newsletters = Newsletter::orderBY('id', 'ASC')->pluck('title', 'id');
        }
        return $newsletters;
    }

    public function getNewsletterBySlug($slug, $loadExtra=null)
    {
        $load = ['mails', 'pages', 'rolemove'];
        if(is_array($loadExtra)) $load = array_merge($load, $loadExtra);
            $newsletter = Newsletter::with($load)->where('slug', $slug)->firstOrFail();
            if(!is_null($newsletter->multisite_sites)) $newsletter->multisite_sites = unserialize($newsletter->multisite_sites);
        return $newsletter;
    }

    public function getNewsletterById($id, $loadExtra=null)
    {
        $load = ['mails', 'pages', 'rolemove'];
        if(is_array($loadExtra)) $load = array_merge($load, $loadExtra);
        $newsletter = Newsletter::with($load)->where('id', $id)->first();
        if(!is_null($newsletter->multisite_sites)) $newsletter->multisite_sites = unserialize($newsletter->multisite_sites);
        return $newsletter;
    }

    public function createNewsletter($request)
    {
        if(!isset($request->visible_in_lists)) $request->request->add(['visible_in_lists' => 0]);
        if(!isset($request->allow_subscribers)) $request->request->add(['allow_subscribers' => 0]);
        if(!isset($request->confirm_non_member)) $request->request->add(['confirm_non_member' => 0]);
        if(!isset($request->autojoin_register)) $request->request->add(['autojoin_register' => 0]);
        $featured_image = isset($request->featured_image) ? url($request->featured_image) : null;
        $request->request->add(['news_code' => str_random(40), 'featured_image' => $featured_image]);

        if(!isset($request->multisite_sites)){
            $request->request->add(['multisite_sites' => null]);
        } else {
            $request->merge(['multisite_sites' => serialize($request->multisite_sites)]);
        }

        $newsletter = Newsletter::create($request->all());

        //Allowed Roles
        $this->syncRoles($newsletter, $request);

        //No Join if subscribed to selected
        $this->syncNoJoin($newsletter, $request);

        //Process Role Change Rules
        $this->syncRoleChange($newsletter, $request);

        //emails
        NewsletterMail::create([
            'newsletter_id' => $newsletter->id,
            'message_type' => 'welcome_email',
            'subject' => $request['welcome_email_subject'],
            'html_message' => $request['welcome_email'],
            'plain_message' => strip_tags(nl2br($request['welcome_email']))
        ]);
        NewsletterMail::create([
            'newsletter_id' => $newsletter->id,
            'message_type' => 'confirmation_email',
            'subject' => $request['confirmation_email_subject'],
            'html_message' => $request['confirmation_email'],
            'plain_message' => strip_tags(nl2br($request['confirmation_email']))
        ]);
        NewsletterMail::create([
            'newsletter_id' => $newsletter->id,
            'message_type' => 'unsubscribe_email',
            'subject' => '',
            'html_message' => $request['unsubscribe_email'],
            'plain_message' => strip_tags(nl2br($request['unsubscribe_email']))
        ]);
        //pages
        NewsletterPage::create([
            'newsletter_id' => $newsletter->id,
            'page_type' => 'subscribed_page',
            'content' => $request['subscribed_page']
        ]);
        NewsletterPage::create([
            'newsletter_id' => $newsletter->id,
            'page_type' => 'unsubscribed_page',
            'content' => $request['unsubscribed_page']
        ]);
        NewsletterPage::create([
            'newsletter_id' => $newsletter->id,
            'page_type' => 'confirmed_email',
            'content' => $request['confirmed_email']
        ]);

        flash('Newsletter has been created.')->success();
        \Activitylogger::log('Admin - Created Newsletter : '.$newsletter->title, $newsletter);
    }

    private function syncRoles($newsletter, $request)
    {
        $roles = [];
        if($request['roles'])
        {
            foreach ($request['roles'] as $key => $role)
            {
                if($role != '1') array_push($roles, $role);
            }
        }
        $newsletter->roles()->sync($roles);
    }

    private function syncNoJoin($newsletter, $request)
    {
        //No Join if subscribed to selected
        if(isset($request['newsletter_no_join']) && count($request['newsletter_no_join']) > 0)
        {
            $nojoin = [];
            foreach($request['newsletter_no_join'] as $key => $newsletter_no_join)
            {
                array_push($nojoin, $newsletter_no_join);
            }
            $newsletter->noJoin()->sync($nojoin);
        }
    }

    private function syncRoleChange($newsletter, $request)
    {
        NewsletterRoleMove::where('newsletter_id', $newsletter->id)->delete();
        $countRoleChange = count($request['role_action']);
        if($countRoleChange > 1)
        {
            $i=1;

            while($i <= $countRoleChange) {
                if(isset($request['role_action'][$i]))
                {
                    NewsletterRoleMove::create([
                        'newsletter_id' => $newsletter->id,
                        'role_action' =>  $request['role_action'][$i],
                        'role_id' =>  $request['role_id'][$i],
                        'newsletter_new_id' =>  $request['newsletter_new_id'][$i],
                        'start_responder' =>  isset($request['start_responder'][$i]) ?: 0,
                    ]);
                }
                $i++;
            }

        }
    }


    public function updateNewsletter($request, $slug)
    {
        $newsletter = $this->getNewsletterBySlug($slug);

        if(!isset($request->visible_in_lists)) $request->request->add(['visible_in_lists' => 0]);
        if(!isset($request->allow_subscribers)) $request->request->add(['allow_subscribers' => 0]);
        if(!isset($request->confirm_non_member)) $request->request->add(['confirm_non_member' => 0]);
        if(!isset($request->autojoin_register)) $request->request->add(['autojoin_register' => 0]);
        $featured_image = isset($request->featured_image) ? url($request->featured_image) : null;
        if(!isset($request->multisite_sites)){
            $request->request->add(['multisite_sites' => null]);
        } else {
            $request->merge(['multisite_sites' => serialize($request->multisite_sites)]);
        }
        $request->request->add(['featured_image' => $featured_image]);

        $newsletter->update($request->all());
        //Allowed Roles
        $this->syncRoles($newsletter, $request);

        //No Join if subscribed to selected
        $this->syncNoJoin($newsletter, $request);

        //Process Role Change Rules
        $this->syncRoleChange($newsletter, $request);

        //emails
        NewsletterMail::where('newsletter_id', $newsletter->id)->where('message_type', 'welcome_email')->update([
            'subject' => $request['welcome_email_subject'],
            'html_message' => $request['welcome_email'],
            'plain_message' => strip_tags(nl2br($request['welcome_email']))
        ]);
        NewsletterMail::where('newsletter_id', $newsletter->id)->where('message_type', 'confirmation_email')->update([
            'subject' => $request['confirmation_email_subject'],
            'html_message' => $request['confirmation_email'],
            'plain_message' => strip_tags(nl2br($request['confirmation_email']))
        ]);
        NewsletterMail::where('newsletter_id', $newsletter->id)->where('message_type', 'unsubscribe_email')->update([
            'html_message' => $request['unsubscribe_email'],
            'plain_message' => strip_tags(nl2br($request['unsubscribe_email']))
        ]);
        //pages
        NewsletterPage::where('newsletter_id', $newsletter->id)->where('page_type', 'subscribed_page')->update([
            'content' => $request['subscribed_page']
        ]);
        NewsletterPage::where('newsletter_id', $newsletter->id)->where('page_type', 'unsubscribed_page')->update([
            'content' => $request['unsubscribed_page']
        ]);
        NewsletterPage::where('newsletter_id', $newsletter->id)->where('page_type', 'confirmed_email')->update([
            'content' => $request['confirmed_email']
        ]);

        flash('Newsletter has been updated.')->success();
        \Activitylogger::log('Admin - Updated Newsletter : '.$newsletter->title, $newsletter);
    }

    public function deleteNewsletter($request)
    {
        $newsletter = $this->getNewsletterBySlug($request->slug);
        if($request->newsletterMove && $request->newsletterMove > 0){

            if($request->start_responder == 'yes')
            {
                NewsletterSubscriber::where('newsletter_id', $newsletter->id)->update([
                    'newsletter_id' => $request->newsletterMove,
                    'sequence' => 1,
                    'sequence_send_on' => Carbon::now()->toDateTimeString()
                ]);
            } else {
                NewsletterSubscriber::where('newsletter_id', $newsletter->id)->update([
                    'newsletter_id' => $request->newsletterMove,
                    'sequence' => 0,
                    'sequence_send_on' => null
                ]);
            }
        }
        $newsletter->importQueue()->delete();
        $newsletter->subscribers()->delete();
        $newsletter->responders()->delete();
        $newsletter->mails()->delete();
        $newsletter->pages()->delete();
        $newsletter->roleMove()->delete();
        $newsletter->sentMail()->delete();
        $newsletter->delete();
        flash('Newsletter has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Newsletter : '.$newsletter->title, $newsletter);
    }

    public function createResponder($request, $slug)
    {
        $newsletter = $this->getNewsletterBySlug($slug);
        $position = 1;

        $lastPos = $newsletter->mails->where('message_type', 'responder')->sortByDesc('position')->first();
        if($lastPos) $position = $lastPos->position + 1;

        $responder = NewsletterMail::create([
            'newsletter_id' => $newsletter->id,
            'message_type' => 'responder',
            'subject' => $request['subject'],
            'html_message' => $request['html_message'],
            'plain_message' => strip_tags(nl2br($request['html_message'])),
            'interval_amount' => $request['interval_amount'],
            'interval_type' => $request['interval_type'],
            'position' => $position
        ]);

        flash('Newsletter Responder has been created.')->success();
        \Activitylogger::log('Admin - Created Newsletter Responder : '.$responder->subject, $newsletter);
    }

    public function updateResponder($request, $newsletter, $id)
    {
        $newsletter = $this->getNewsletterBySlug($newsletter);
        $responder = NewsletterMail::where('newsletter_id', $newsletter->id)->where('id', $id)->first();
        $responder->subject = $request['subject'];
        $responder->html_message = $request['html_message'];
        $responder->plain_message = strip_tags(nl2br($request['html_message']));
        $responder->interval_amount = $request['interval_amount'];
        $responder->interval_type = $request['interval_type'];
        $responder->Save();
        flash('Newsletter Responder has been updated.')->success();
        \Activitylogger::log('Admin - Updated Newsletter Responder : '.$responder->subject, $newsletter);
    }

    public function updateResponderPositions($request, $newsletter)
    {
        $newsletter = $this->getNewsletterBySlug($newsletter);
        $i=1;
        foreach ($request->position as $position)
        {
            NewsletterMail::where('newsletter_id', $newsletter->id)->where('message_type', 'responder')->where('id', $position)->update([
                'position' => $i
            ]);
            $i++;
        }
        flash('positions updated.')->success();
    }

    public function responderDelete($newsletter, $id)
    {
        $newsletter = $this->getNewsletterBySlug($newsletter);
        $newsletter->load('responders');
        $found = false;
        foreach ($newsletter->responders as $responder)
        {
            if($responder->id == $id) {
                $responder->delete();
                $found = true;
                continue;
            }
            if($found){
                $responder->position = $responder->position -1;
                $responder->save();
            }

        }
        flash('Newsletter Responder has been removed.')->success();
        \Activitylogger::log('Admin - Removed Newsletter Responder : '.$responder->subject, $newsletter);
    }

}