<?php

namespace Quantum\tickets\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Quantum\tickets\Services\TicketService;

class AdminComposer
{

    protected $user;
    /**
     * @var TicketService
     */
    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->user = \Auth::user();
        $this->ticketService = $ticketService;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('ticketAlertCount', $this->ticketService->ticketCount());
    }
}