<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestIdDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$id);
    }

    /**
     * @var string
     */
    public $id;

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
