<?php

namespace Quantum\tickets\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Services\FirewallService;
use Quantum\tickets\Http\Requests\Admin\Create_Ticket_Status_Request;
use Quantum\tickets\Http\Requests\Frontend\Create_Ticket_Reply_Request;
use Quantum\tickets\Http\Requests\Frontend\Create_Ticket_Request;
use Quantum\tickets\Models\TicketStatus;
use Quantum\tickets\Services\TicketService;
use Quantum\tickets\Services\TicketSettingsService;
use Yajra\DataTables\Facades\DataTables;

class Tickets extends Controller
{

    /**
     * @var TicketSettingsService
     */
    private $ticketSettingsService;
    /**
     * @var TicketService
     */
    private $ticketService;

    /**
     * Tickets constructor.
     * @param TicketSettingsService $ticketSettingsService
     * @param TicketService $ticketService
     */
    public function __construct(TicketSettingsService $ticketSettingsService, TicketService $ticketService)
    {
        $this->ticketSettingsService = $ticketSettingsService;
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = $this->ticketSettingsService->getDepartmentsList();
        $pageSnippet = $this->ticketService->getPageSnippet('Support FrontPage');
        return view('tickets::frontend.create', compact('departments', 'pageSnippet'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create_Ticket_Request $request)
    {
        $this->ticketService->createPublicTicket($request);
        $page = \Settings::get('ticket_public_received_page');
        if($page == '') return redirect('/support');
        return redirect($page);
    }


    public function show($id)
    {
        $ticket = $this->ticketService->getPublicTicket($id);
        return view('tickets::frontend.show', compact('ticket'));
    }

    public function reply(Create_Ticket_Reply_Request $request, $id)
    {
        $this->ticketService->publicReply($request, $id);
        return redirect('/support/ticket/'.$id);
    }
}
