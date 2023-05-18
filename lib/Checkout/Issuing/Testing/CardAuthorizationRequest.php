<?php

namespace Checkout\Issuing\Testing;

class CardAuthorizationRequest
{
    /**
     * @var CardSimulation
     */
    public $card;

    /**
     * @var TransactionSimulation
     */
    public $transaction;
}
