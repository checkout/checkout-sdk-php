<?php

namespace Checkout\Payments;

class PayoutRequest
{
    // PaymentRequestDestination
    public $destination;

    public $amount;

    // FundTransferType
    public $fund_transfer_type;

    public $currency;

    public $payment_type;

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

    public $purpose;

    // PaymentRecipient
    public $recipient;

    public $metadata;

    public $processing;
}
