<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : MembershipListener.php
 **/

namespace Quantum\base\Listeners;


use Quantum\base\Services\MembershipService;

class MembershipListener
{

    /**
     * @var MembershipService
     */
    private $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    /**
     * Handle membership purchase events.
     */
    public function purchased($event) 
    {
        $this->membershipService->membershipPurchased($event);
    }
    
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Quantum\base\Events\MembershipPurchased',
            'Quantum\base\Listeners\MembershipListener@purchased'
        );

    }
}