<?php

namespace Checkout\Payments;

use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\Destination\PaymentRequestDestination;
use DateTime;

class PayoutRequest
{
    /**
     * @var PaymentRequestDestination
     */
    public $destination;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var FundTransferType
     */
    public $fund_transfer_type;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var PaymentType
     */
    public $payment_type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $capture;

    /**
     * @var DateTime
     */
    public $capture_on;

    /**
     * @var CustomerRequest
     */
    public $customer;

    /**
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * @var string
     */
    public $previous_payment_id;

    /**
     * @var RiskRequest
     */
    public $risk;

    /**
     * @var string
     */
    public $success_url;

    /**
     * @var string
     */
    public $failure_url;

    /**
     * @var string
     */
    public $payment_ip;

    /**
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var array
     */
    public $processing;
}
