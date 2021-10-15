<?php

namespace App\Event;

use App\Entity\Purchase;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PurchaseSuccessEvent extends Event{
    private $purchase;

    public function __construct(Purchase $purchase) {
        $this->purchase = $purchase;
    }

    public function getPurchase() : Purchase {
        return $this->purchase;
    }
}