<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\AccountHolder;
use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

/**
 * A network token payment source.
 */
class RequestNetworkTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$network_token);
    }

    /**
     * The network token PAN.
     * [Required]
     * @var string
     */
    public $token;

    /**
     * The expiry month of the token.
     * [Required]
     * min 1
     * @var int
     */
    public $expiry_month;

    /**
     * The expiry year of the token.
     * [Required]
     * @var int
     */
    public $expiry_year;

    /**
     * The type of token.
     * [Required]
     * Enum: "vts" "mdes" "applepay" "googlepay"
     * @var string value of NetworkTokenType
     */
    public $token_type;

    /**
     * The Base64 encoded cryptographic identifier (TAVV) used by card schemes to validate the token
     * verification result. Optional if previous_payment_id is specified and 3ds.enabled is false.
     * [Required]
     * max 100 characters
     * @var string
     */
    public $cryptogram;

    /**
     * The Electronic Commerce Indicator (ECI) security level associated with the token. Optional if
     * previous_payment_id is specified and 3ds.enabled is false.
     * [Required]
     * max 2 characters
     * @var string
     */
    public $eci;

    /**
     * Indicates whether this card is being submitted from your own stored card-on-file system, rather
     * than being entered by the customer at the time of payment. Set to true when you provide card details
     * that you already have on file for the customer.
     * [Optional]
     * Default: false
     * @var bool
     */
    public $stored;

    /**
     * This must be set to true if you intend to reuse the payment credentials in subsequent payments.
     * Setting the field as false will mean that a payment instrument will not be included in the payment
     * response.
     * [Optional]
     * Default: true
     * @var bool
     */
    public $store_for_future_use;

    /**
     * The customer's name.
     * [Optional]
     * @var string
     */
    public $name;

    /**
     * The card verification value/code. 3 digits, except for American Express (4 digits).
     * [Optional]
     * min 3 characters
     * max 4 characters
     * @var string
     */
    public $cvv;

    /**
     * The customer's billing address.
     * [Optional]
     * @var Address
     */
    public $billing_address;

    /**
     * The customer's phone number.
     * [Optional]
     * @var Phone
     */
    public $phone;

    /**
     * Information about the account holder of the card.
     * [Optional]
     * @var AccountHolder|null $account_holder
     */
    public $account_holder;
}
