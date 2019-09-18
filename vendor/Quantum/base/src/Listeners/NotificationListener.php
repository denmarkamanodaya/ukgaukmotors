<?php

namespace Quantum\base\Listeners;

use Quantum\base\Models\User;
use Quantum\base\Models\NotificationEvents;
use Quantum\base\Services\NotificationService;

class NotificationListener
{

    /**
     * @var NotificationService
     */
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }


    /**
    * Handle user login events.
    */
    public function onUserLogin($event) {
        $this->notificationService->eventCaptured($event, 'Illuminate\Auth\Events\Login');
    }

    /**
     * Handle user register event.
     */
    public function onUserRegister($event) {
        $this->notificationService->eventCaptured($event->user, 'UserRegistered');
    }

    /**
     * Handle user upgrade event.
     */
    public function onUserUpgrade($event) {
        $user = User::with(['membership' => function ($query) use ($event) {
            $query->with('membership');
            $query->where('id', $event->userMembership->id);
        }])
            ->where('id', $event->userMembership->user_id)
            ->first();
        $this->notificationService->eventCaptured($user, 'UserUpgraded');
    }

    /**
     * Handle user payment event.
     */
    public function onPaymentReceived($event) {

        $userId = ($event->userPurchase->user_id != 0) ? $event->userPurchase->user_id : '1';

        $user = User::where('id', $userId)->first();
        $user->transaction = $event->transaction;
        $user->userPurchase = $event->userPurchase;

        $this->notificationService->eventCaptured($user, 'PaymentReceived');
    }
    
    public function onMemberTest($event) {
        $this->notificationService->userEventCaptured($event, 'MemberTest');
    }

    public function ticketCreated($event) {
        $this->notificationService->eventCaptured($event, 'TicketCreated');
    }

    public function ticketReplied($event) {
        $this->notificationService->eventCaptured($event, 'TicketReplied');
    }





    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Quantum\base\Listeners\NotificationListener@onUserLogin'
        );

        $events->listen(
            'Quantum\base\Events\UserRegistered',
            'Quantum\base\Listeners\NotificationListener@onUserRegister'
        );

        $events->listen(
            'Quantum\base\Events\UserUpgraded',
            'Quantum\base\Listeners\NotificationListener@onUserUpgrade'
        );

        $events->listen(
            'Quantum\base\Events\PaymentReceived',
            'Quantum\base\Listeners\NotificationListener@onPaymentReceived'
        );

        $events->listen(
            'Quantum\base\Events\MemberTest',
            'Quantum\base\Listeners\NotificationListener@onMemberTest'
        );

        $events->listen(
            'Quantum\tickets\Events\TicketCreated',
            'Quantum\base\Listeners\NotificationListener@ticketCreated'
        );

        $events->listen(
            'Quantum\tickets\Events\TicketReplied',
            'Quantum\base\Listeners\NotificationListener@ticketReplied'
        );

    }

}