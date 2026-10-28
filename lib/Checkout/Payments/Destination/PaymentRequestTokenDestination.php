<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\AccountHolder;
use Checkout\Payments\PaymentDestinationType;

/**
 * A token payout destination.
 * Card payout destination with type "token".
 */
class PaymentRequestTokenDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$token);
    }

    /**
     * The Checkout.com card token ID. This will be an ID with the prefix tok_.
     * [Required]
     * ^(tok)_(\w{26})$
     * min 30 characters
     * max 30 characters
     * @var string
     */
    public $token;

    /**
     * The payout destination account holder.
     * [Required]
     * Discriminated by type: "individual" "corporate" "government".
     * Accepted keys for individual: type, first_name, middle_name, last_name.
     * Accepted keys for corporate and government: type, company_name.
     * @var AccountHolder
     */
    public $account_holder;
}
