<?php

namespace Checkout\Payments\Destination;

/**
 * The destination of the payout.
 * You can encrypt this field by using the Cko-Encryption-Key-Id and Cko-Encrypted-Properties headers.
 */
class PaymentRequestDestination
{
    /**
     * The payout destination type. Set by each subclass.
     * [Required]
     * @var string value of PaymentDestinationType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
