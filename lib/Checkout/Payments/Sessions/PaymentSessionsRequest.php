<?php

namespace Checkout\Payments\Sessions;

use Checkout\Payments\Request\PaymentRetryRequest;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\PaymentCustomerRequest;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class PaymentSessionsRequest
{
    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * @var BillingInformation
     */
    public $billing;

    /**
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * @var PaymentCustomerRequest
     */
    public $customer;

    /**
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * @var string
     */
    public $processing_channel_id;

    /**
     * @var DateTime
     */
    public $expires_on;

    /**
     * @var PaymentMethodConfiguration
     */
    public $payment_method_configuration;

    /**
     * @var array of PaymentMethodsType
     */
    public $enabled_payment_methods;

    /**
     * @var array of PaymentMethodsType
     */
    public $disabled_payment_methods;

     /**
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * @var RiskRequest
     */
    public $risk;

    /**
     * @var PaymentRetryRequest
     */
    public $retry;

    /**
     * @var string
     */
    public $display_name;

    /**
     * @var string
     */
    public $success_url;

    /**
     * @var string
     */
    public $failure_url;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * @var PaymentSender
     */
    public $sender;

    /**
     * @var bool
     */
    public $capture;

    /**
     * @var DateTime
     */
    public $capture_on;

    /**
     * @var string
     */
    public $ip_address;

    /**
     * @var int
     */
    public $tax_amount;
}
