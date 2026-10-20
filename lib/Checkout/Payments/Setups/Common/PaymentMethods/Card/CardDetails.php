<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Card;

use DateTime;

/**
 * The card details for the Card payment method.
 */
class CardDetails
{
    /**
     * The card number (without separators).
     * [Optional]
     * writeOnly
     * @var string
     */
    public $number;

    /**
     * The last four digits of the card number.
     * [Optional]
     * readOnly
     * @var string
     */
    public $last4;

    /**
     * The card issuer's Bank Identification Number (BIN).
     * [Optional]
     * readOnly
     * @var string
     */
    public $bin;

    /**
     * The card scheme.
     * [Optional]
     * readOnly
     * @var string
     */
    public $scheme;

    /**
     * The expiry month of the card.
     * [Optional]
     * @var int
     */
    public $expiry_month;

    /**
     * The expiry year of the card.
     * [Optional]
     * @var int
     */
    public $expiry_year;

    /**
     * The cardholder's name.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $name;

    /**
     * The card verification value/code. 3 digits, except for American Express (4 digits).
     * [Optional]
     * writeOnly
     * @var string
     */
    public $cvv;

    /**
     * Indicates whether this card is being submitted from your own stored card-on-file system, rather than
     * being entered by the customer at the time of payment. Set to true when you provide card details that
     * you already have on file for the customer.
     * [Optional]
     * writeOnly
     * @var bool
     */
    public $stored;

    /**
     * The time by which the card details must be confirmed.
     * [Optional]
     * readOnly
     * Format: date-time (RFC 3339)
     * @var DateTime
     */
    public $expires_on;

    /**
     * Indicates whether to use the Real-Time Account Updater to update the card information.
     * [Optional]
     * @var bool
     */
    public $allow_update;
}
