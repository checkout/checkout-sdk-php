<?php

namespace Checkout\Payments\Sessions;

use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\CustomerRetry;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\PaymentCustomerRequest;
use Checkout\Payments\PaymentPlan;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class PaymentSessionsRequest
{
    /**
     * The payment amount. Provide a value of `0` to perform a card verification. The amount must be provided in the
     * minor currency unit.
     * [Required]
     * min 0
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO currency code.
     * [Required]
     * max 3 characters
     * min 3 characters
     * @var string value of Currency
     */
    public $currency;

    /**
     * Must be specified for card-not-present (CNP) payments. For example, a recurring mail order / telephone order
     * (MOTO) payment.
     * [Optional]
     * Default: "Regular"
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * The billing details.
     * [Required]
     * @var BillingInformation
     */
    public $billing;

    /**
     * A description of the purchase, which is displayed on the customer's statement.
     * [Optional]
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * A reference you can use to identify the payment. For example, an order number. - For Amex payments, this must
     * be at most 30 characters. - For Benefit payments, the reference must be a unique alphanumeric value. - For
     * iDEAL payments, the reference is required and must be an alphanumeric value with a 35-character limit.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $reference;

    /**
     * A description for the payment.
     * [Optional]
     * max 100 characters
     * @var string
     */
    public $description;

    /**
     * The customer's details. Required if `source.type` is `tamara`.
     * [Optional]
     * @var PaymentCustomerRequest
     */
    public $customer;

    /**
     * The shipping details
     * [Optional]
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * Information about the recipient of the payment's funds. Applies to Account Funding Transactions, and VISA or
     * Mastercard domestic UK transactions processed by financial institutions.
     * [Optional]
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * Use the processing object to influence or override the data sent during card processing
     * [Optional]
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * The processing channel to use for the payment.
     * [Optional]
     * ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;

    /**
     * A timestamp specifying when the `PaymentSession` should expire, as an ISO 8601 code. If no value is provided,
     * expiry is set to 24 hours after the `PaymentSession` is created. You cannot set the session expiry to more
     * than 60 days after the `PaymentSession` is created.
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var DateTime
     */
    public $expires_on;

    /**
     * Configurations for payment method-specific settings.
     * [Optional]
     * @var PaymentMethodConfiguration
     */
    public $payment_method_configuration;

    /**
     * Specifies which payment method options to present to the customer. The values in this field override any
     * equivalent values in `disabled_payment_methods`.
     * [Optional]
     * @var array of PaymentMethodsType
     */
    public $enabled_payment_methods;

    /**
     * Specifies which payment method options to not present to the customer. If you specify the same payment method
     * in this field and in `enabled_payment_methods`, the `disabled_payment_methods` value will be overridden. Any
     * payment method options not explicitly specified in this field will be presented to the customer by default.
     * [Optional]
     * @var array of PaymentMethodsType
     */
    public $disabled_payment_methods;

    /**
     * The line items in the order.
     * [Optional]
     * min 1 items
     * max 1000 items
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * The sub-entities that the payment is being processed on behalf of.
     * [Optional]
     * min 1 items
     * max 50 items
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * Configures the risk assessment performed during payment processing.
     * [Optional]
     * @var RiskRequest
     */
    public $risk;

    /**
     * Configuration for asynchronous retries.
     * [Optional]
     * @var CustomerRetry|null $customer_retry
     */
    public $customer_retry;

    /**
     * Details about the payment instruction.
     * [Optional]
     * @var PaymentInstruction|null $instruction
     */
    public $instruction;

    /**
     * The merchant's display name.
     * [Optional]
     * max 255 characters
     * @var string
     */
    public $display_name;

    /**
     * Overrides the default success redirect URL configured on your account, for payment methods that require a
     * redirect.
     * [Required]
     * Format: uri
     * max 1024 characters
     * @var string
     */
    public $success_url;

    /**
     * Overrides the default failure redirect URL configured on your account, for payment methods that require a
     * redirect.
     * [Required]
     * Format: uri
     * max 1024 characters
     * @var string
     */
    public $failure_url;

    /**
     * Allows you to store additional information about a transaction with custom fields and up to five user-defined
     * fields, which can be used for reporting purposes. You can supply fields of type `string`, `number`, and
     * `boolean` within the `metadata` object. Arrays and objects are not supported. You can provide up to 18
     * metadata fields per API call, but the value of each field must not exceed 255 characters in length. You can
     * also reference `metadata` properties in your custom rules for Fraud Detection. For example, `$coupon_code =
     * '1234'`.
     * [Optional]
     * @var array
     */
    public $metadata;

    /**
     * Creates a translated version of the page in the specified language.
     * [Optional]
     * Enum: "ar" "da-DK" "de-DE" "el" "en-GB" "es-ES" "fi-FI" "fil-PH" "fr-FR" "hi-IN" "id-ID" "it-IT" "ja-JP"
     * "ko-KR" "ms-MY" "nb-NO" "nl-NL" "pt-PT" "sv-SE" "th-TH" "vi-VN" "zh-CN" "zh-HK" "zh-TW"
     * Default: "en-GB"
     * @var string
     */
    public $locale;

    /**
     * [Optional]
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * The sender of the payment.
     * [Optional]
     * @var PaymentSender
     */
    public $sender;

    /**
     * Specifies whether to capture the payment, if applicable.
     * [Optional]
     * Default: true
     * @var bool
     */
    public $capture;

    /**
     * A timestamp specifying when to capture the payment, as an ISO 8601 code. If a value is provided, `capture` is
     * automatically set to `true`.
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var DateTime
     */
    public $capture_on;

    /**
     * The Customers IP address. Only IPv4 and IPv6 addresses are accepted.
     * [Optional]
     * Format: ipv4
     * max 45 characters
     * @deprecated
     * @var string
     */
    public $ip_address;

    /**
     * The authorization type.
     * [Optional]
     * Enum: "Final" "Estimated"
     * Default: "Final"
     * @var string|null $authorization_type value of AuthorizationType
     */
    public $authorization_type;

    /**
     * The information to process a recurring payment request. To be used when the payment_type is Recurring.
     * [Optional]
     * @var PaymentPlan|null $payment_plan
     */
    public $payment_plan;
}
