<?php

namespace Checkout\Payments\Hosted;

use Checkout\Common\CustomerRequest;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\PaymentPlan;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\CustomerRetry;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\Sessions\PaymentMethodConfiguration;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class HostedPaymentsSessionRequest
{
    /**
     * The payment amount. The exact format depends on the currency
     * [Optional]
     * min 0
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO currency code of the payment
     * [Required]
     * max 3 characters
     * min 3 characters
     * @var string value of Currency
     */
    public $currency;

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
     * A description of the payment
     * [Optional]
     * max 100 characters
     * @var string
     */
    public $description;

    /**
     * The merchant name to display to customers on the checkout page.
     * [Optional]
     * @var string
     */
    public $display_name;

    /**
     * The customer's details
     * [Optional]
     * @var CustomerRequest
     */
    public $customer;

    /**
     * The address any products are being sent to.
     * [Optional]
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * The billing details
     * [Required]
     * @var BillingInformation
     */
    public $billing;

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
     * Contains details about the products in the order.
     * [Optional]
     * min 1 items
     * max 1000 items
     * @var array of Product
     */
    public $products;

    /**
     * Allows you to store additional information about the transaction. This object only allows one level of depth,
     * so cannot accept non-primitive data types such as objects or arrays.
     * [Optional]
     * @var array
     */
    public $metadata;

    /**
     * Information required for 3D Secure payments
     * [Optional]
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * Configures the risk assessment performed during payment processing.
     * [Optional]
     * @var RiskRequest
     */
    public $risk;

    /**
     * For redirect payment methods, this overrides the default success redirect URL configured on your account
     * [Required]
     * Format: uri
     * max 255 characters
     * @var string
     */
    public $success_url;

    /**
     * The URL to which the customer should be directed if they cancel the payment
     * [Required]
     * Format: uri
     * max 255 characters
     * @var string
     */
    public $cancel_url;

    /**
     * For redirect payment methods, this overrides the default failure redirect URL configured on your account
     * [Required]
     * Format: uri
     * max 255 characters
     * @var string
     */
    public $failure_url;

    /**
     * Creates a translated version of the page in the specified language
     * [Optional]
     * Enum: "ar" "da-DK" "de-DE" "el" "en-GB" "es-ES" "fi-FI" "fil-PH" "fr-FR" "hi-IN" "id-ID" "it-IT" "ja-JP"
     * "ms-MY" "nb-NO" "nl-NL" "pt-PT" "sv-SE" "th-TH" "vi-VN" "zh-CN" "zh-HK" "zh-TW"
     * Default: "en-GB"
     * @var string
     */
    public $locale;

    /**
     * Whether to capture the payment (if applicable).
     * [Optional]
     * @var bool
     */
    public $capture;

    /**
     * A timestamp (ISO 8601 code) that determines when the payment should be captured. Providing this field will
     * automatically set `capture` to true.
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var DateTime
     */
    public $capture_on;

    /**
     * This must be specified for card payments where the cardholder is not present (i.e., recurring or mail order /
     * telephone order)
     * [Optional]
     * Default: "Regular"
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * The Customers IP address. Only IPv4 and IPv6 addresses are accepted.
     * [Optional]
     * Format: ipv4
     * max 45 characters
     * @deprecated
     * @var string
     */
    public $payment_ip;

    /**
     * An optional description that is displayed on the customer's statement identifying a purchase
     * [Optional]
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * Specifies which payment method options to present to the customer. The values in this field override any
     * equivalent values in `disabled_payment_methods`. Some payment methods have additional field requirements. See
     * the documentation for more information.
     * [Optional]
     * @var string[] value of PaymentSourceType
     */
    public $allow_payment_methods;

    /**
     * Specifies which payment method options to not present to the customer. If you specify the same payment method
     * in this field and in `allow_payment_methods`, the `disabled_payment_methods` value will be overridden. Any
     * payment method options not explicitly specified in this field will be presented to the customer by default.
     * [Optional]
     * @var string[] values of PaymentSourceType
     */
    public $disabled_payment_methods;

    /**
     * Configures the parameters for customer retries. A customer retry is a payment attempt performed by the
     * customer on the checkout page. This differs from authorization request retries performed in the back end by
     * Checkout.com.
     * [Optional]
     * @var CustomerRetry|null $customer_retry
     */
    public $customer_retry;

    /**
     * The sender of the payment.
     * [Optional]
     * @var PaymentSender
     */
    public $sender;

    /**
     * Details about the payment instruction.
     * [Optional]
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * Configuration options for specific payment methods.
     * [Optional]
     * @var PaymentMethodConfiguration
     */
    public $payment_method_configuration;

    //Not available on previous

    /**
     * The processing channel to be used for the payment
     * [Optional]
     * ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;

    /**
     * The sub-entities that the payment is being processed on behalf of
     * [Optional]
     * min 1 items
     * max 50 items
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * The information to process a recurring payment request. To be used when the payment_type is Recurring.
     * [Optional]
     * @var PaymentPlan|null $payment_plan
     */
    public $payment_plan;

    /**
     * The authorization type.
     * [Optional]
     * Enum: "Final" "Estimated"
     * Default: "Final"
     * @var string|null $authorization_type value of AuthorizationType
     */
    public $authorization_type;
}
