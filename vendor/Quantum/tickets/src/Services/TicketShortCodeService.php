<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : TicketShortCodeService.php
 **/

namespace Quantum\tickets\Services;


class TicketShortCodeService
{
    private $content;
    private $ticket;

    public function emailSubject($content, $ticket)
    {
        $this->setContent($content);
        $sitename = config('app.name');
        $subject = '['.$sitename.' #'.$ticket->id.'] '.$ticket->title;
        $this->doreplace('ticketSubject', $subject);
        $this->removeUnused();
        return $this->content;
    }

    public function membersEmail($content, $ticket)
    {
        $this->setContent($content);
        $this->setTicket($ticket);
        $this->ticketUrl();
        $this->ticketTitle();
        $this->ticketReply();
        $this->userDetails();
        $this->removeUnused();
        return $this->content;
    }

    public function publicEmail($content, $ticket)
    {
        $this->setContent($content);
        $this->setTicket($ticket);
        $this->ticketPublicUrl();
        $this->ticketTitle();
        $this->ticketReply();
        $this->removeUnused();
        return $this->content;
    }


    private function ticketUrl()
    {
        if($this->ticket->user_id > 0)
        {
            $url = url('/members/support/ticket/'.$this->ticket->id);
            $this->doreplace('ticketUrl', $url);
        }
    }

    private function ticketPublicUrl()
    {
        if(!is_null($this->ticket->public_key))
        {
            $url = url('/support/ticket/'.$this->ticket->public_key);
            $this->doreplace('ticketUrl', $url);
        }
    }

    private function ticketTitle()
    {
        $this->doreplace('ticketTitle', $this->ticket->title);
    }

    private function ticketReply()
    {
        $this->doreplace('ticketReply', nl2br($this->ticket->reply->content));
        $this->doreplace('ticketReplyText', $this->ticket->reply->content);
    }

    public function userDetails()
    {
        $this->doreplace('username', $this->ticket->user->username);
        $this->doreplace('firstname', $this->ticket->user->profile->first_name);
        $this->doreplace('lastname', $this->ticket->user->profile->last_name);
        $this->doreplace('email', $this->ticket->user->email);

        $this->doreplace('address', $this->ticket->user->profile->address);
        $this->doreplace('address2', $this->ticket->user->profile->address2);
        $this->doreplace('city', $this->ticket->user->profile->city);
        $this->doreplace('county', $this->ticket->user->profile->county);
        $this->doreplace('postcode', $this->ticket->user->profile->postcode);
        $this->doreplace('country', $this->ticket->user->profile->country);
        $this->doreplace('telephone', $this->ticket->user->profile->telephone);
        $this->doreplace('bio', $this->ticket->user->profile->bio);
    }


    private function setContent($content)
    {
        $this->content = $content;
    }

    private function setTicket($ticket)
    {
        $this->ticket = $ticket;
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