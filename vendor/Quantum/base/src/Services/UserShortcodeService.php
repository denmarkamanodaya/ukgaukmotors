<?php

namespace Quantum\base\Services;


class UserShortcodeService
{
    private $user;
    private $content;
    private $event;

    public function __construct()
    {
        $this->user = \Auth::user();
    }

    public function membersPage($content)
    {
        $this->setContent($content);
        $this->userDetails();
        $this->baseInclude('page');
        $this->removeUnused();

        return $this->content;
    }

    public function membersEmail($content, $user = null, $event = null)
    {

        if($user) $this->setUser($user);
        if($event) $this->setEvent($event);
        $this->setContent($content);
        $this->userDetails();
        $this->urls();
        $this->membership();
        $this->transaction();
        $this->invoice();
        $this->ticket();
        $this->verifyEmailUrl();
        $this->verifyEmailText();

        $this->baseInclude('email');

        return $this->content;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function userDetails()
    {
        if(!$this->user) return;
        $this->doreplace('username', $this->user->username);
        $this->doreplace('firstname', $this->user->profile->first_name);
        $this->doreplace('lastname', $this->user->profile->last_name);
        $this->doreplace('email', $this->user->email);

        $this->doreplace('address', $this->user->profile->address);
        $this->doreplace('address2', $this->user->profile->address2);
        $this->doreplace('city', $this->user->profile->city);
        $this->doreplace('county', $this->user->profile->county);
        $this->doreplace('postcode', $this->user->profile->postcode);
        $this->doreplace('country', $this->user->profile->country);
        $this->doreplace('telephone', $this->user->profile->telephone);
        $this->doreplace('bio', $this->user->profile->bio);
        

    }

    public function urls()
    {
        $this->doreplace('loginurl', url('login'));
    }
    
    public function membership()
    {
        if(isset($this->user->membership)) {
            foreach($this->user->membership as $membership)
            {
                $this->doreplace('upgradeMembership', $membership->membership->title);
            }

        }
    }
    
    public function transaction()
    {

        if(isset($this->event->transaction))
        {
            $sitecountry = \Countries::siteCountry();
           $amount =  $this->event->transaction->amount;
            $this->doreplace('payment-amount', $sitecountry->currency_symbol.$amount);
        }
        if(isset($this->event->userPurchase->purchasedItems))
        {
            $items = '';
            foreach ($this->event->userPurchase->purchasedItems as $item)
            {
                $items .= $item.',';
            }
            $items = rtrim($items, ',');
            $this->doreplace('payment-items', $items);
        }
    }

    public function invoice()
    {
        if(isset($this->user->invoice))
        {

            $this->doreplace('invoice-id', $this->user->invoice->id);
            $this->doreplace('invoice-full-amount', $this->user->invoice->amount);
        }
    }

    public function ticket()
    {
        if(isset($this->event->ticket))
        {
            $this->doreplace('ticket-title', $this->event->ticket->title);
            $this->doreplace('ticket-content', $this->event->ticket->content);
        }
    }

    public function verifyEmailUrl()
    {
        if(!$this->user) return;
        $confirmUrl = '';
        if($this->user->email_confirmed != 'true')
        {
            if($this->user->email_code != null)
            {
            $confirmUrl = url('/confirm-email/'.$this->user->email_code);
            }
        }
        $this->doreplace('verifyEmailUrl', $confirmUrl);
    }

    public function verifyEmailText()
    {
        if(!$this->user) return;
        $verifyText = '';
        if($this->user->email_confirmed != 'true')
        {
            if($this->user->email_code != null)
            {
                $verifyText = 'To verify your email address please click on the following link<br><a href="'.url('/confirm-email/'.$this->user->email_code).'">Verify Email Address</a> ';
            }
        }
        $this->doreplace('verifyEmailText', $verifyText);
    }

    public function baseInclude($type)
    {
        if(\File::exists(app_path('/Services/UserShortcodeService.php')))
        {
            $class = 'App\Services\UserShortcodeService';

            $appShortcode = \App::make($class);
            $this->content = $appShortcode->doReplace($type, $this->content, $this->user);
        }
    }
    
    public function replace($content, $search, $replace)
    {
        $this->setContent($content);
        $this->doreplace($search, $replace);
        return $this->content;
    }

    private function setContent($content)
    {
        $this->content = $content;
    }

    private function doreplace($search, $replacement)
    {
        $this->content = str_replace('['.$search.']', $replacement, $this->content);
    }

    private function removeUnused()
    {
        $pattern = '/\[(\w+)\]/';
        $this->content = preg_replace($pattern, '', $this->content);
    }


}