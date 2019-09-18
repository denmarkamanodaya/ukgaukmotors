<?php

namespace Quantum\tickets\Http\Controllers\Members;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\tickets\Http\Requests\Admin\Create_Ticket_Status_Request;
use Quantum\tickets\Http\Requests\Members\Create_Ticket_Reply_Request;
use Quantum\tickets\Http\Requests\Members\Create_Ticket_Request;
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
        $tickets = $this->ticketService->getUserTickets();
        return view('tickets::members.index', compact('tickets'));
    }

    public function create()
    {
        $departments = $this->ticketSettingsService->getDepartmentsList();
        return view('tickets::members.create', compact('departments'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create_Ticket_Request $request)
    {
        $this->ticketService->createTicket($request);
        return redirect('/members/support');
    }


    public function openTickets()
    {
        $closedTickets = TicketStatus::where('slug', 'closed')->firstOrFail();
        $tickets = $this->ticketService->getUserTicketsBuilder([$closedTickets->id]);
        return Datatables::of($tickets)
            ->editColumn('updated_at', function ($model) {
                return [
                    'display' => e(
                        $model->updated_at->diffForHumans()
                    ),
                    'timestamp' => $model->updated_at->timestamp
                ];
            })
            ->editColumn('title', function ($model) {
                return '<a href="'. url('/members/support/ticket/'.$model->id) .'">'.$model->title.'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }

    public function closedTickets()
    {
        $openTickets = TicketStatus::where('slug', '!=', 'closed')->get();
        $openArray = [];
        foreach ($openTickets as $open)
        {
            array_push($openArray, $open->id);
        }

        $tickets = $this->ticketService->getUserTicketsBuilder($openArray);
        return Datatables::of($tickets)
            ->editColumn('updated_at', function ($model) {
                return [
                    'display' => e(
                        $model->updated_at->diffForHumans()
                    ),
                    'timestamp' => $model->updated_at->timestamp
                ];
            })
            ->editColumn('title', function ($model) {
                return '<a href="'. url('/members/support/ticket/'.$model->id) .'">'.$model->title.'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getTicket($id);
        return view('tickets::members.show', compact('ticket'));
    }

    public function reply(Create_Ticket_Reply_Request $request, $id)
    {
        $this->ticketService->reply($request, $id);
        return redirect('/members/support/ticket/'.$id);
    }
}
