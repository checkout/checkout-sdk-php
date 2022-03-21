<?php

namespace Checkout\Risk\PreCapture;

class PreCaptureAssessmentRequest
{
    public $assessment_id;

    // DateTime
    public $date;

    // RiskPaymentRequestSource
    public $source;

    // CustomerRequest
    public $customer;

    public $amount;

    public $currency;

    // RiskPayment
    public $payment;

    // RiskShippingDetails
    public $shipping;

    // Device
    public $device;

    public $metadata;

    // AuthenticationResult
    public $authentication_result;

    // AuthorizationResult
    public $authorization_result;
}
