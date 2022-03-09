<?php

namespace Checkout\Risk\preauthentication;

use Checkout\Common\CustomerRequest;
use Checkout\Risk\Device;
use Checkout\Risk\source\RiskPaymentRequestSource;
use Checkout\Risk\RiskPayment;
use Checkout\Risk\RiskShippingDetails;
use DateTime;

class PreAuthenticationAssessmentRequest
{
    public DateTime $date;

    public RiskPaymentRequestSource $source;

    public CustomerRequest $customer;

    public RiskPayment $payment;

    public RiskShippingDetails $shipping;

    public string $reference;

    public string $description;

    public int $amount;

    public string $currency;

    public Device $device;

    public array $metadata;
}
