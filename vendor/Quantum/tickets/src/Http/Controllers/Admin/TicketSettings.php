<?php

namespace Quantum\tickets\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Services\PageService;
use Quantum\tickets\Http\Requests\Admin\Create_Ticket_Status_Request;
use Quantum\tickets\Http\Requests\Admin\Update_Ticket_Settings_Request;
use Quantum\tickets\Http\Requests\Members\Create_Ticket_Reply_Request;
use Quantum\tickets\Http\Requests\Members\Create_Ticket_Request;
use Quantum\tickets\Models\TicketStatus;
use Quantum\tickets\Services\TicketService;
use Quantum\tickets\Services\TicketSettingsService;

class TicketSettings extends Controller
{

    /**
     * @var TicketSettingsService
     */
    private $ticketSettingsService;
    /**
     * @var PageService
     */
    private $pageService;
    /**
     * @var TicketService
     */


    /**
     * Tickets constructor.
     * @param TicketSettingsService $ticketSettingsService
     * @param TicketService $ticketService
     */
    public function __construct(TicketSettingsService $ticketSettingsService, PageService $pageService)
    {
        $this->ticketSettingsService = $ticketSettingsService;
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = $this->pageService->getPageList('public', false);
        return view('tickets::admin.Settings.index', compact('pages'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Update_Ticket_Settings_Request $request)
    {
        $this->ticketSettingsService->updateSettings($request);
        return redirect('/admin/ticket/settings');
    }

}
