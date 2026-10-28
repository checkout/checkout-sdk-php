<?php

namespace Checkout\Payments\Sender;

class PaymentSender
{
    /**
     * @var string value of PaymentSenderType
     */
    public $type;

    /**
     * The unique reference for the sender. For example, a customer number.
     * For card payouts, only alphanumeric characters are supported. For bank payouts, a unique
     * reference to the underlying client's customer (a global KYC requirement).
     * [Required] for card payouts and bank payouts, [Optional] for payments
     * min 5 characters and max 15 characters for card payouts
     * @var string
     */
    public $reference;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
