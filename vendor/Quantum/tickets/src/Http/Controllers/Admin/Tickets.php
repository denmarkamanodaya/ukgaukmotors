<?php

namespace Quantum\tickets\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\tickets\Http\Requests\Admin\Change_Ticket_Status_Request;
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
        $ticketCount = [];
        $ticketCount['Replied'] = 0;
        $tickets = $this->ticketService->getAllTickets();
        $tickets = $tickets->groupBy('status.name');
        foreach($tickets as $key => $ticket)
        {
            if(in_array($key, ['Open', 'Closed']))
            {
                $ticketCount[$key] = $ticket->count();
                $ticketCount[$key.'_Latest'] = $ticket->first()->updated_at;
            } else {
                $ticketCount['Replied'] = $ticketCount['Replied'] + $ticket->count();
                $ticketCount['Replied_Latest'] = $ticket->first()->updated_at;
            }
        }
        unset($tickets);
        return view('tickets::admin.index', compact('ticketCount'));
    }

    public function create()
    {
        $departments = $this->ticketSettingsService->getDepartmentsList();
        return view('tickets::admin.create', compact('departments'));
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
        return redirect('/admin/tickets');
    }


    public function openTickets()
    {
        $openTickets = TicketStatus::where('slug', 'open')->firstOrFail();
        $tickets = $this->ticketService->getAllTicketsBuilder(null,[$openTickets->id]);
        return Datatables::eloquent($tickets)
            //->editColumn('id', '{!! Form::checkbox(\'deleteId[]\', $id, null, array(\'class\' => \'styled\')) !!}')
            ->editColumn('id', function ($model) {
                return \Form::checkbox('deleteId[]', $model->id, null, array('class' => 'styled'));
            })
            //->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->diffForHumans();
            })
            //->editColumn('title', '<a href="{!! url(\'/admin/tickets/ticket/\'.$id) !!}">{{$title}}</a>')
            ->editColumn('title', function ($model) {
                return '<a href="'. url('/admin/tickets/ticket/'.$model->id) .'">'.$model->title.'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }

    public function repliedTickets()
    {
        $repliedTickets = TicketStatus::whereNotIn('slug', ['closed', 'open'])->get();
        $repliedArray = [];
        foreach($repliedTickets as $reply)
        {
            array_push($repliedArray, $reply->id);
        }

        $tickets = $this->ticketService->getAllTicketsBuilder(null,$repliedArray);
        return Datatables::eloquent($tickets)
            //->editColumn('id', '{!! Form::checkbox(\'deleteId[]\', $id, null, array(\'class\' => \'styled\')) !!}')
            ->editColumn('id', function ($model) {
                return \Form::checkbox('deleteId[]', $model->id, null, array('class' => 'styled'));
            })
            //->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('updated_at', function ($model) {
                return [
                    'display' => e(
                        $model->updated_at->diffForHumans()
                    ),
                    'timestamp' => $model->updated_at->timestamp
                ];
            })
            //->editColumn('title', '<a href="{!! url(\'/admin/tickets/ticket/\'.$id) !!}">{{$title}}</a>')
            ->editColumn('title', function ($model) {
                return '<a href="'. url('/admin/tickets/ticket/'.$model->id) .'">'.$model->title.'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }

    public function closedTickets()
    {
        $closedTickets = TicketStatus::where('slug', 'closed')->first();


        $tickets = $this->ticketService->getAllTicketsBuilder(null,[$closedTickets->id]);
        return Datatables::eloquent($tickets)
            //->editColumn('id', '{!! Form::checkbox(\'deleteId[]\', $id, null, array(\'class\' => \'styled\')) !!}')
            ->editColumn('id', function ($model) {
                return \Form::checkbox('deleteId[]', $model->id, null, array('class' => 'styled'));
            })
            //->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('updated_at', function ($model) {
                return [
                    'display' => e(
                        $model->updated_at->diffForHumans()
                    ),
                    'timestamp' => $model->updated_at->timestamp
                ];
            })
            //->editColumn('title', '<a href="{!! url(\'/admin/tickets/ticket/\'.$id) !!}">{{$title}}</a>')
            ->editColumn('title', function ($model) {
                return '<a href="'. url('/admin/tickets/ticket/'.$model->id) .'">'.$model->title.'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getAdminTicket($id);
        $statuses = $this->ticketSettingsService->getStatusList();
        return view('tickets::admin.show', compact('ticket', 'statuses'));
    }

    public function reply(Create_Ticket_Reply_Request $request, $id)
    {
        $this->ticketService->replyAdmin($request, $id);
        return redirect('/admin/tickets/ticket/'.$id);
    }

    public function changeStatus(Change_Ticket_Status_Request $request, $id)
    {
        $this->ticketService->changeStatus($request, $id);
        return redirect('/admin/tickets/ticket/'.$id);
    }

    public function delete(Request $request, $id)
    {
        $this->ticketService->deleteTicket($id);
        return redirect('/admin/tickets');
    }

    public function deleteTickets(Request $request)
    {
        $this->ticketService->deleteTickets($request);
        return redirect('/admin/tickets');
    }
}
