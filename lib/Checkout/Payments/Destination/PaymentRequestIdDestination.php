<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;
use Checkout\Common\AccountHolder;

/**
 * A payment instrument payout destination.
 * Destination with type "id", valid for card payouts, bank payouts and unreferenced refunds
 * (UnreferencedRefundRequest), same wire shape.
 */
class PaymentRequestIdDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$id);
    }

    /**
     * The payment source ID. This will be an ID with the prefix src_.
     * [Required]
     * ^(src)_(\w{26})$
     * min 30 characters
     * max 30 characters
     * @var string
     */
    public $id;

    /**
     * The payout destination account holder, or the unreferenced refund destination account holder.
     * [Required] for card payouts. [Optional] for unreferenced refunds. Not declared by the bank
     * payout id destination.
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
