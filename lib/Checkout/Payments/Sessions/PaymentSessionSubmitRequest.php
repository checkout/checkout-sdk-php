<?php

namespace Checkout\Payments\Sessions;

class PaymentSessionSubmitRequest
{
    /**
     * A unique token representing the additional customer data captured by Flow.
     * @var string
     */
    public $session_data;

    /**
     * The payment amount in minor currency units. Provide 0 for card verification.
     * @var int
     */
    public $amount;

    /**
     * A reference to identify the payment (e.g., order number).
     * @var string
     */
    public $reference;

    /**
     * The line items in the order.
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * Information required for 3D Secure authentication payments.
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * The customer's IP address. Only IPv4 and IPv6 addresses are accepted.
     * @var string
     */
    public $ip_address;

    /**
     * Must be specified for card-not-present (CNP) payments.
     * Values: Regular, Recurring, MOTO, Installment, Unscheduled
     * @var string value of PaymentType
     */
    public $payment_type;
}
