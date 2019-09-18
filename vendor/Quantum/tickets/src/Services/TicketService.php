<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : TicketService.php
 **/

namespace Quantum\tickets\Services;


use Cache;
use Mail;
use Quantum\base\Mail\General;
use Quantum\base\Models\Emailing;
use Quantum\base\Services\FirewallService;
use Quantum\base\Services\NewsService;
use Quantum\tickets\Events\TicketCreated;
use Quantum\tickets\Events\TicketReplied;
use Quantum\tickets\Models\TicketReplies;
use Quantum\tickets\Models\Tickets;
use Quantum\tickets\Models\TicketStatus;
use Quantum\base\Models\MailLog;

class TicketService
{
    protected $user;
    /**
     * @var TicketShortCodeService
     */
    private $ticketShortCodeService;
    /**
     * @var FirewallService
     */
    private $fw;
    /**
     * @var NewsService
     */
    private $newsService;

    public function __construct(TicketShortCodeService $ticketShortCodeService, FirewallService $fw, NewsService $newsService)
    {
        $this->ticketShortCodeService = $ticketShortCodeService;
        $this->fw = $fw;
        $this->newsService = $newsService;
    }

    public function getUserTickets($except=null)
    {
        $user = \Auth::user();
        if($except && is_array($except))
        {
            $tickets = Tickets::with(['status', 'department'])->where('user_id', $user->id)->whereNotIn('ticket_status_id', $except)->orderBy('updated_at', 'DESC')->get();
        } else {
            $tickets = Tickets::with(['status', 'department'])->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->get();
        }
        //$tickets = $tickets->groupBy('status_id');
        return $tickets;
    }

    public function getUserTicketsBuilder($except=null)
    {
        $user = \Auth::user();
        if($except && is_array($except))
        {
            $tickets = Tickets::with(['status', 'department'])->where('user_id', $user->id)->whereNotIn('ticket_status_id', $except)->orderBy('updated_at', 'DESC');
        } else {
            $tickets = Tickets::with(['status', 'department'])->where('user_id', $user->id)->orderBy('updated_at', 'DESC');
        }
        //$tickets = $tickets->groupBy('status_id');
        return $tickets;
    }

    public function getAllTickets($except=null, $only=null)
    {
        if($except && is_array($except))
        {
            $tickets = Tickets::with(['status', 'department'])->whereNotIn('ticket_status_id', $except)->orderBy('updated_at', 'DESC')->get();
        } elseif($only && is_array($only)) {
            $tickets = Tickets::with(['status', 'department'])->whereIn('ticket_status_id', $only)->orderBy('updated_at', 'DESC')->get();
        } else {
            $tickets = Tickets::with(['status', 'department'])->orderBy('updated_at', 'DESC')->get();
        }
        //$tickets = $tickets->groupBy('status_id');
        return $tickets;
    }

    public function getAllTicketsBuilder($except=null, $only=null)
    {
        if($except && is_array($except))
        {
            $tickets = Tickets::with(['status', 'department'])->whereNotIn('ticket_status_id', $except)->orderBy('updated_at', 'DESC');
        } elseif($only && is_array($only)) {
            $tickets = Tickets::with(['status', 'department'])->whereIn('ticket_status_id', $only)->orderBy('updated_at', 'DESC');
        } else {
            $tickets = Tickets::with(['status', 'department'])->orderBy('updated_at', 'DESC');
        }
        //$tickets = $tickets->groupBy('status_id');
        return $tickets;
    }

    public function createTicket($request)
    {
        $user = \Auth::user();
        $ticketStatus = TicketStatus::where('default', 1)->first();


        $ticket = Tickets::create([
            'ticket_department_id' => $request->department,
            'ticket_status_id' => $ticketStatus->id,
            'user_id' => $user->id,
            'title' => $request->subject,
            'content' => $request->content
        ]);

        $ticket->user = $user;

        flash('Ticket has been created.')->success();
        \Activitylogger::log('Created Support Ticket : '.$ticket->title, $ticket);
        Cache::forget('ticketCount');
        \Event::fire(new TicketCreated($ticket));
        return $ticket;

    }

    public function getTicket($id)
    {
        $this->user = \Auth::user();
        $ticket = Tickets::with(['status', 'department', 'replies', 'staff'])->where('user_id', $this->user->id)->where('id', $id)->firstOrFail();
        return $ticket;
    }

    public function getAdminTicket($id)
    {
        $ticket = Tickets::with(['status', 'department', 'replies', 'staff', 'user'])->where('id', $id)->firstOrFail();
        return $ticket;
    }

    public function reply($request, $id)
    {
        $ticket = $this->getTicket($id);
        $ticketStatus = TicketStatus::where('slug', 'user_replied')->first();
        if($ticket->status->slug == 'closed')
        {
            flash('You can not reply to a closed ticket.')->success();
            return;
        }

        $reply = TicketReplies::create([
            'tickets_id' => $ticket->id,
            'user_id' => $this->user->id,
            'content' => $request->content
        ]);

        if($ticketStatus)
        {
            $ticket->ticket_status_id = $ticketStatus->id;
            $ticket->save();
        }

        flash('A reply has been added.')->success();
        \Activitylogger::log('Replied to Support Ticket : '.$ticket->title, $ticket);
        Cache::forget('ticketCount');
        $ticket->user = $this->user;
        \Event::fire(new TicketReplied($ticket));
        return;
    }

    public function replyAdmin($request, $id)
    {
        $ticket = $this->getAdminTicket($id);
        if($ticket->status->slug == 'closed')
        {
            flash('You can not reply to a closed ticket.')->success();
            return;
        }

        $reply = TicketReplies::create([
            'tickets_id' => $ticket->id,
            'staff_id' => \Auth::user()->id,
            'content' => $request->content
        ]);


            $ticket->ticket_status_id = $request->status;
            $ticket->save();

            $ticket->reply = $reply;
            $this->mailTicketOwner($ticket);

        flash('A reply has been added.')->success();
        \Activitylogger::log('Replied to Support Ticket : '.$ticket->title, $ticket);
        Cache::forget('ticketCount');
        return;
    }


    private function mailTicketOwner($ticket)
    {
        if($ticket->user)
        {
            $data['to'] = $ticket->user->email;
            $systemMail = 'Notification - Ticket Reply Members';
        } else {
            $data['to'] = $ticket->email;
            $systemMail = 'Notification - Ticket Reply Public';
        }
        if(!$data['to'] || $data['to'] == '') return;

        $mailContent = Emailing::where('title', $systemMail)->tenant()->firstOrFail();

        $data['subject'] = $this->ticketShortCodeService->emailSubject($mailContent->subject, $ticket);
        if($ticket->user)
        {
            $data['content_html'] = $this->ticketShortCodeService->membersEmail($mailContent->content_html, $ticket);
            $data['content_text'] = $this->ticketShortCodeService->membersEmail($mailContent->content_text, $ticket);
        } else {
            $data['content_html'] = $this->ticketShortCodeService->publicEmail($mailContent->content_html, $ticket);
            $data['content_text'] = $this->ticketShortCodeService->publicEmail($mailContent->content_text, $ticket);
        }

        $data['from'] = \Settings::get('ticket_email_from_address');
        $data['from_name'] = \Settings::get('site_email_from_name');
        $data['template'] = 'emails.general';


        Mail::to($data['to'])->queue(new General($data));
        if(!$ticket->user)
        {
            $this->mailLog($data);
        } else {
            $this->mailLog($data, $ticket->user);
        }

    }

    private function mailLog($data, $user=null)
    {
        $mailllog = new MailLog();
        $mailllog->user_id = is_object($user) ? $user->id : $user;
        $mailllog->subject = $data['subject'];
        $mailllog->content_html = $data['content_html'];
        $mailllog->content_text = isset($data['content_text']) ? $data['content_text'] : '';
        $mailllog->save();
    }

    public function changeStatus($request, $id)
    {
        $ticket = $this->getAdminTicket($id);
        $ticket->ticket_status_id = $request->status;
        $ticket->save();
        flash('Ticket status has been changed.')->success();
        Cache::forget('ticketCount');
    }

    public function deleteTicket($id)
    {
        $ticket = $this->getAdminTicket($id);
        $ticket->replies()->delete();
        $ticket->delete();
        flash('Ticket has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Ticket : '.$ticket->title, $ticket);
        Cache::forget('ticketCount');
        return;
    }

    public function deleteTickets($request)
    {
        if(!is_array($request->deleteId)) return;
        foreach ($request->deleteId as $ticketId)
        {
            $ticket = $this->getAdminTicket($ticketId);
            $ticket->replies()->delete();
            $ticket->delete();
            \Activitylogger::log('Admin - Deleted Ticket : '.$ticket->title, $ticket);
        }
        flash('Selected Tickets have been deleted.')->success();
        Cache::forget('ticketCount');
        return;
    }

    public function createPublicTicket($request)
    {
        $ticketStatus = TicketStatus::where('default', 1)->first();

        $ticket = Tickets::create([
            'ticket_department_id' => $request->department,
            'ticket_status_id' => $ticketStatus->id,
            'user_id' => null,
            'email' => $request->email,
            'title' => $request->subject,
            'content' => $request->content,
            'public_key' => str_random(40),
        ]);

        flash('Ticket has been created.')->success();
        \Activitylogger::log('Created Support Ticket : '.$ticket->title, $ticket);
        Cache::forget('ticketCount');
        \Event::fire(new TicketCreated($ticket));
        return $ticket;

    }

    public function getPublicTicket($id)
    {
        $ticket = Tickets::with(['status', 'department', 'replies', 'staff'])->whereNull('user_id')->where('public_key', $id)->first();
        if(!$ticket)
        {
            $this->fw->init();
            $this->fw->failure('Invalid Public Ticket ID');
            abort(404);
        }
        return $ticket;
    }

    public function publicReply($request, $id)
    {
        $ticket = $this->getPublicTicket($id);
        $ticketStatus = TicketStatus::where('slug', 'user_replied')->first();
        if($ticket->status->slug == 'closed')
        {
            flash('You can not reply to a closed ticket.')->error();
            return;
        }

        $reply = TicketReplies::create([
            'tickets_id' => $ticket->id,
            'user_id' => null,
            'content' => $request->content
        ]);

        if($ticketStatus)
        {
            $ticket->ticket_status_id = $ticketStatus->id;
            $ticket->save();
        }

        flash('A reply has been added.')->success();
        \Activitylogger::log('Replied to Support Ticket : '.$ticket->title, $ticket);
        Cache::forget('ticketCount');
        \Event::fire(new TicketReplied($ticket));
        return;
    }

    public function ticketCount()
    {
        if(!\Auth::user()->can('view-admin-area')) return false;
        $ticketCount = Cache::remember('ticketCount', 60, function () {
            $tickets = [];
            $statuses = TicketStatus::all();
            foreach ($statuses as $status)
            {
                if($status->slug == 'open')
                {
                    $tickets['open'] = Tickets::where('ticket_status_id', $status->id)->count();
                } elseif($status->slug == 'closed') {
                    continue;
                } elseif($status->slug == 'awaiting_reply') {
                    $tickets['awaiting_reply'] = Tickets::where('ticket_status_id', $status->id)->count();
                } elseif($status->slug == 'user_replied') {
                    $tickets['user_replied'] = Tickets::where('ticket_status_id', $status->id)->count();
                } elseif($status->slug == 'staff_replied') {
                    $tickets['staff_replied'] = Tickets::where('ticket_status_id', $status->id)->count();
                }
            }

            return $tickets;
        });

        return $ticketCount;

    }

    public function getPageSnippet($title)
    {
        return $this->newsService->getSnippet($title, 'public');
    }

}