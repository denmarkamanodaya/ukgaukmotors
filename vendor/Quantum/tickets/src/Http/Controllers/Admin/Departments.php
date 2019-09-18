<?php

namespace Quantum\tickets\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\tickets\Http\Requests\Admin\Create_Ticket_Status_Request;
use Quantum\tickets\Services\TicketSettingsService;

class Departments extends Controller
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
        $departments = $this->ticketSettingsService->getDepartments();
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('tickets::admin.Departments.index', compact('departments', 'fajson'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create_Ticket_Status_Request $request)
    {
        $this->ticketSettingsService->createDepartment($request);
        return redirect('/admin/ticket/departments');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = $this->ticketSettingsService->getDepartmentBySlug($id);
        $departments = $this->ticketSettingsService->getDepartments();
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('tickets::admin.Departments.edit', compact('department', 'departments', 'fajson'));
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
        $this->ticketSettingsService->editDepartment($request, $id);
        return redirect('/admin/ticket/departments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->ticketSettingsService->deleteDepartment($id);
        return redirect('/admin/ticket/departments');
    }
}
