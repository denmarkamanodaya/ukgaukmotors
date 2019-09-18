<?php

namespace Quantum\base\Events;

use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Quantum\base\Models\Transactions;
use Quantum\base\Models\UserPurchase;

class PaymentReceived extends Event
{
    use SerializesModels;
    /**
     * @var Transactions
     */
    public $transaction;
    /**
     * @var UserPurchase
     */
    public $userPurchase;

    /**
     * Create a new event instance.
     *
     * @param Transactions $transaction
     */
    public function __construct(Transactions $transaction, UserPurchase $userPurchase)
    {
        $this->transaction = $transaction;
        $this->userPurchase = $userPurchase;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
