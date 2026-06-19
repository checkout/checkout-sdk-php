<?php

namespace Checkout\Payments\Links;

use Checkout\Common\CustomerRequest;
use Checkout\Common\MarketplaceData;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\PaymentRetryRequest;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\Sessions\PaymentMethodConfiguration;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class PaymentLinkRequest
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
    public $expires_in;

    /**
     * @var CustomerRequest
     */
    public $customer;

    /**
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * @var BillingInformation
     */
    public $billing;

    /**
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * @var array of Product
     */
    public $products;

    /**
     * @var RiskRequest
     */
    public $risk;

    /**
     * @var string
     */
    public $return_url;

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
     * @var bool
     */
    public $capture;

    /**
     * @var DateTime
     */
    public $capture_on;

    /**
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * @var string
     */
    public $payment_ip;

    /**
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * @var array of PaymentSourceType
     */
    public $allow_payment_methods;

    /**
     * The payment methods to hide from the payment link.
     * [Optional]
     * @var string[]|null $disabled_payment_methods values of PaymentSourceType
     */
    public $disabled_payment_methods;

    /**
     * The name of the merchant or seller to show on the payment link page.
     * [Optional]
     * @var string|null $display_name
     */
    public $display_name;

    /**
     * Configuration for retrying failed payments.
     * [Optional]
     * @var PaymentRetryRequest|null $customer_retry
     */
    public $customer_retry;

    /**
     * The sender of the payment.
     * [Optional]
     * @var PaymentSender|null $sender
     */
    public $sender;

    /**
     * Details about the instruction for payouts to bank accounts.
     * [Optional]
     * @var PaymentInstruction|null $instruction
     */
    public $instruction;

    /**
     * Configuration for the payment methods shown on the payment link.
     * [Optional]
     * @var PaymentMethodConfiguration|null $payment_method_configuration
     */
    public $payment_method_configuration;

    //Not available on previous

    /**
     * @var string
     */
    public $processing_channel_id;

    /**
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * The authorization type.
     * [Optional]
     * Allowed values: "Final", "Estimated". Defaults to "Final".
     * @var string|null $authorization_type
     */
    public $authorization_type;

    /**
     * The payment plan details. To be used when the payment_type is Recurring.
     * [Optional]
     * @var \Checkout\Payments\PaymentPlan|null $payment_plan
     */
    public $payment_plan;
}
