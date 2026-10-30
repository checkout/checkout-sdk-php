<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\AccountHolder;
use Checkout\Payments\PaymentDestinationType;

/**
 * A token payout destination.
 * Card payout destination with type "token".
 * Also the "token" destination of an unreferenced refund (UnreferencedRefundRequest), same wire shape.
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
     * The payout destination account holder, or the unreferenced refund destination account holder.
     * [Required] for card payouts. [Optional] for unreferenced refunds.
     * Discriminated by type: "individual" "corporate" "government".
     * Accepted keys for card payouts, individual: type, first_name, middle_name, last_name.
     * Accepted keys for card payouts, corporate and government: type, company_name.
     * Accepted keys for unreferenced refunds, individual: type, first_name, middle_name, last_name,
     * billing_address, phone, identification, email, date_of_birth, country_of_birth.
     * Accepted keys for unreferenced refunds, corporate and government: type, company_name,
     * billing_address, phone, identification, email.
     * @var AccountHolder
     */
    public $account_holder;
}
