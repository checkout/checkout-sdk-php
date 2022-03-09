<?php

namespace Checkout\Risk\precapture;

use Checkout\Common\CustomerRequest;
use Checkout\Risk\Device;
use Checkout\Risk\source\RiskPaymentRequestSource;
use Checkout\Risk\RiskPayment;
use Checkout\Risk\RiskShippingDetails;
use DateTime;

class PreCaptureAssessmentRequest
{
    public string $assessment_id;

    public DateTime $date;

    public RiskPaymentRequestSource $source;

    public CustomerRequest $customer;

    public int $amount;

    public string $currency;

    public RiskPayment $payment;

    public RiskShippingDetails $shipping;

    public Device $device;

    public array $metadata;

    public AuthenticationResult $authentication_result;

    public AuthorizationResult $authorization_result;
}
