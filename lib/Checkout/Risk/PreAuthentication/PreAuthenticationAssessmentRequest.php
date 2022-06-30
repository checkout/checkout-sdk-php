<?php

namespace Checkout\Risk\PreAuthentication;

use Checkout\Common\CustomerRequest;
use Checkout\Risk\Device;
use Checkout\Risk\RiskPayment;
use Checkout\Risk\RiskShippingDetails;
use Checkout\Risk\Source\RiskPaymentRequestSource;
use DateTime;

class PreAuthenticationAssessmentRequest
{
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
     * @var RiskPayment
     */
    public $payment;

    /**
     * @var RiskShippingDetails
     */
    public $shipping;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var Device
     */
    public $device;

    /**
     * @var array
     */
    public $metadata;
}
