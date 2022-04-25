<?php

namespace Checkout\Payments\Four\Request;

use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\MarketplaceData;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\Four\AuthorizationType;
use Checkout\Payments\Four\Request\Source\AbstractRequestSource;
use Checkout\Payments\Four\Sender\PaymentSender;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class PaymentRequest
{
    /**
     * @var AbstractRequestSource
     */
    public $source;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var AuthorizationType
     */
    public $payment_type;

    /**
     * @var bool
     */
    public $merchant_initiated;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * @var AuthorizationType
     */
    public $authorization_type;

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
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * @var string
     */
    public $processing_channel_id;

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
     * @var PaymentSender
     */
    public $sender;

    /**
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * @var MarketplaceData
     */
    public $marketplace;

    /**
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var array of Four/Product
     */
    public $items;
}
