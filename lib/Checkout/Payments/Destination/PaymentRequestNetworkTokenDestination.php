<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\AccountHolder;
use Checkout\Payments\PaymentDestinationType;

/**
 * A network token payout destination.
 * Card payout destination with type "network_token".
 * Also the "network_token" destination of an unreferenced refund (UnreferencedRefundRequest), same
 * wire shape.
 */
class PaymentRequestNetworkTokenDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$network_token);
    }

    /**
     * The network token's Primary Account Number (PAN).
     * [Required]
     * @var string
     */
    public $token;

    /**
     * The network token's expiration month.
     * [Required]
     * min 1
     * @var int
     */
    public $expiry_month;

    /**
     * The network token's expiration year.
     * [Required]
     * @var int
     */
    public $expiry_year;

    /**
     * The network token type.
     * [Required]
     * Enum: "vts" "mdes" "applepay" "googlepay"
     * @var string value of NetworkTokenType
     */
    public $token_type;

    /**
     * The network token's Base64-encoded cryptographic identifier (TAVV).
     * The cryptogram is used by card schemes to validate the token verification result.
     * [Optional]
     * max 50 characters
     * @var string|null
     */
    public $cryptogram;

    /**
     * The network token's Electronic Commerce Indicator (ECI) security level.
     * [Optional]
     * max 2 characters
     * @var string|null
     */
    public $eci;

    /**
     * The payout destination account holder. For unreferenced refunds, the unreferenced refund
     * destination account holder, with the same keys; individual requires first_name and last_name.
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
