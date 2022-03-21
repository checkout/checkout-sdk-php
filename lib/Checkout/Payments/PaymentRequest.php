<?php

namespace Checkout\Payments;

class PaymentRequest
{
    // AbstractRequestSource
    public $source;

    public $amount;

    public $currency;

    public $payment_type;

    public $merchant_initiated;

    public $reference;

    public $description;

    public $capture;

    // DateTime
    public $capture_on;

    // CustomerRequest
    public $customer;

    // BillingDescriptor
    public $billing_descriptor;

    // ShippingDetails
    public $shipping;

    public $previous_payment_id;

    // RiskRequest
    public $risk;

    public $success_url;

    public $failure_url;

    public $payment_ip;

    // ThreeDsRequest
    public $three_ds;

    // PaymentRecipient
    public $recipient;

    public $metadata;

    public $processing;

}
