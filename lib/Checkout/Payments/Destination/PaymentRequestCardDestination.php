<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\AccountHolder;
use Checkout\Payments\PaymentDestinationType;

/**
 * A card payout destination.
 * Card payout destination with type "card".
 */
class PaymentRequestCardDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$card);
    }

    /**
     * The card number.
     * You can encrypt this field by using the Cko-Encryption-Key-Id and Cko-Encrypted-Properties headers.
     * [Required]
     * max 19 characters
     * @var string
     */
    public $number;

    /**
     * The card's expiration month.
     * [Required]
     * @var int
     */
    public $expiry_month;

    /**
     * The card's expiration year.
     * [Required]
     * @var int
     */
    public $expiry_year;

    /**
     * The payout destination account holder.
     * [Required]
     * Discriminated by type: "individual" "corporate" "government".
     * Accepted keys for individual: type, first_name, middle_name, last_name, billing_address,
     * phone, identification, email, date_of_birth, country_of_birth.
     * Accepted keys for corporate and government: type, company_name, billing_address, phone,
     * identification, email.
     * @var AccountHolder
     */
    public $account_holder;
}
