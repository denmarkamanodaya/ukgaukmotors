<?php

namespace Quantum\tickets\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\tickets\Http\Requests\Admin\Create_Ticket_Status_Request;
use Quantum\tickets\Services\TicketSettingsService;

class TicketStatus extends Controller
{

    /**
     * @var TicketSettingsService
     */
    private $ticketSettingsService;

    public function __construct(TicketSettingsService $ticketSettingsService)
    {
        $this->ticketSettingsService = $ticketSettingsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = $this->ticketSettingsService->getStatuses();
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('tickets::admin.Status.index', compact('statuses', 'fajson'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create_Ticket_Status_Request $request)
    {
        $this->ticketSettingsService->createStatus($request);
        return redirect('/admin/ticket/statuses');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->ticketSettingsService->getStatusBySlug($id);
        $statuses = $this->ticketSettingsService->getStatuses();
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('tickets::admin.Status.edit', compact('status', 'statuses', 'fajson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Create_Ticket_Status_Request $request, $id)
    {
        $this->ticketSettingsService->editStatus($request, $id);
        return redirect('/admin/ticket/statuses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->ticketSettingsService->deleteStatus($id);
        return redirect('/admin/ticket/statuses');
    }
}
