<?php

namespace Checkout\Payments\Previous\Destination;

use Checkout\Common\Address;
use Checkout\Common\Phone;
use Checkout\Payments\PaymentDestinationType;

class PaymentRequestTokenDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$token);
    }

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
