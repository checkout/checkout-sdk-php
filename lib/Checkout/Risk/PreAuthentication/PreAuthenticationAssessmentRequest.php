<?php

namespace Checkout\Risk\PreAuthentication;

class PreAuthenticationAssessmentRequest
{
    // DateTime
    public $date;

    // RiskPaymentRequestSource
    public $source;

    // CustomerRequest
    public $customer;

    // RiskPayment
    public $payment;

    // RiskShippingDetails
    public $shipping;

    public $reference;

    public $description;

    public $amount;

    public $currency;

    // Device
    public $device;

    public $metadata;
}
