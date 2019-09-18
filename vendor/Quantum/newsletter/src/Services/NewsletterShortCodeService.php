<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : NewsletterShortCodeService.php
 **/

namespace Quantum\newsletter\Services;


class NewsletterShortCodeService
{
    private  $subscriber;
    private $content;

    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function subscriberEmail($content, $subscriber)
    {

        $this->setSubscriber($subscriber);
        $this->setContent($content);
        $this->userDetails();
        $this->verifyCode();
        $this->unsubscribeUrl();
        $this->removeUnused();

        return $this->content;
    }

    public function justClean($content)
    {
        $this->setContent($content);
        $this->removeUnused();

        return $this->content;
    }

    public function userDetails()
    {
        $this->doreplace('username', isset($this->subscriber->user->username)?:'');
        $this->doreplace('firstname', $this->subscriber->first_name);
        $this->doreplace('lastname', $this->subscriber->last_name);
        $this->doreplace('email', $this->subscriber->email);

        $this->doreplace('address', isset($this->subscriber->user->profile->address)?:'');
        $this->doreplace('address2', isset($this->subscriber->user->profile->address2)?:'');
        $this->doreplace('city', isset($this->subscriber->user->profile->city)?:'');
        $this->doreplace('county', isset($this->subscriber->user->profile->county)?:'');
        $this->doreplace('postcode', isset($this->subscriber->user->profile->postcode)?:'');
        $this->doreplace('country', isset($this->subscriber->user->profile->country)?:'');
        $this->doreplace('telephone', isset($this->subscriber->user->profile->telephone)?:'');
        $this->doreplace('bio', isset($this->subscriber->user->profile->bio)?:'');

        $this->doreplace('subCode', $this->subscriber->sub_code);
    }

    public function verifyCode()
    {
        $url = url('/newsletter/subscriber/confirm/'.$this->subscriber->sub_code);
        $this->doreplace('confirmEmailUrl', $url);
    }

    public function unsubscribeUrl()
    {
        if($this->subscriber->newsletter)
        {
            $url = url('/newsletter/unsubscribe/'.$this->subscriber->newsletter->news_code.'/'.$this->subscriber->sub_code);
            $this->doreplace('unsubscribeUrl', $url);
        }

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