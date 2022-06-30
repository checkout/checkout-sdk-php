<?php

namespace Checkout\Risk\PreCapture;

use Checkout\Common\CustomerRequest;
use Checkout\Risk\Device;
use Checkout\Risk\RiskPayment;
use Checkout\Risk\RiskShippingDetails;
use Checkout\Risk\Source\RiskPaymentRequestSource;
use DateTime;

class PreCaptureAssessmentRequest
{
    /**
     * @var string
     */
    public $assessment_id;

    /**
     * @var DateTime
     */
    public $date;

    /**
     * @var RiskPaymentRequestSource
     */
    public $source;

    /**
     * @var CustomerRequest
     */
    public $customer;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var RiskPayment
     */
    public $payment;

    /**
     * @var RiskShippingDetails
     */
    public $shipping;

    /**
     * @var Device
     */
    public $device;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var AuthenticationResult
     */
    public $authentication_result;

    /**
     * @var AuthorizationResult
     */
    public $authorization_result;
}
