<?php

namespace Checkout\Payments\Request;

use Checkout\Common\CustomerRequest;
use Checkout\Common\MarketplaceData;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\InstructionRemittance;
use Checkout\Payments\PaymentInstruction;
use Checkout\Payments\PaymentPlan;
use Checkout\Payments\Request\PaymentRouting;
use Checkout\Payments\Request\PaymentSubscription;
use Checkout\Payments\Request\Source\AbstractRequestSource;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class PaymentRequest
{
    /**
     * @var string
     */
    public $payment_context_id;

    /**
     * @var AbstractRequestSource
     */
    public $source;

    /**
     * The fallback source to use if the primary source is unavailable.
     * [Optional]
     * @var AbstractRequestSource|null $fallback_source
     */
    public $fallback_source;

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
     * The details of a recurring subscription or installment payment plan.
     * [Optional]
     * @var PaymentPlan|null $payment_plan
     */
    public $payment_plan;

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
     * @var Authentication
     */
    public $authentication;

    /**
     * @var string value of AuthorizationType
     */
    public $authorization_type;

    /**
     * @var PartialAuthorization
     */
    public $partial_authorization;

    /**
     * @var bool
     */
    public $capture;

    /**
     * @var DateTime
     */
    public $capture_on;

    /**
     * The date and time when the Multibanco payment expires in UTC.
     * [Optional]
     * Format: ISO 8601
     * @var DateTime|null $expire_on
     */
    public $expire_on;

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
     * @var PaymentSegment
     */
    public $segment;

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
     * Obsolete - Use risk.device.network.ipv4 or risk.device.network.ipv6 instead
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
     * @deprecated This property will be removed in the future, and should be used {@link amount_allocations} instead
     * @var MarketplaceData
     */
    public $marketplace;

    /**
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * @var PaymentRetryRequest
     */
    public $retry;

    /**
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * The details linking a series of recurring payments together.
     * [Optional]
     * @var PaymentSubscription|null $subscription
     */
    public $subscription;

    /**
     * Controls processor attempts at the payment level.
     * [Optional]
     * @var PaymentRouting|null $routing
     */
    public $routing;
}
