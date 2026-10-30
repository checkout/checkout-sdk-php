<?php

namespace Checkout\Payments\Sender;

/**
 * The sender of a payout or a payment.
 * Shared by card payouts and bank payouts (PayoutRequest) and by payments (PaymentRequest, also typed on
 * the hosted payments, payment links and payment sessions requests). Required flags and length
 * constraints differ per position, as noted on each property; per-position notes cover card payouts,
 * bank payouts and regular payments (PaymentRequest).
 * Card payouts: required for money transfer card payouts; types "individual" "corporate" "government".
 * Bank payouts: the ultimate sender of the payout, that is, your customer; not supported for Platform
 * or Direct Seller Checkout.com clients; types "instrument" "individual" "corporate".
 * Payments: information about the sender of the payment's funds; types "individual" "corporate"
 * "instrument".
 */
class PaymentSender
{
    /**
     * The sender type, set by the constructor of each subclass.
     * For card payouts, the type of entity performing the payout. For bank payouts and payments, if set
     * to instrument, the sender information is retrieved from the destination (bank payouts) or the
     * payment instrument (payments).
     * [Required]
     * Enum for card payouts: "individual" "corporate" "government"
     * Enum for bank payouts: "instrument" "individual" "corporate"
     * Enum for payments: "individual" "corporate" "instrument"
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
