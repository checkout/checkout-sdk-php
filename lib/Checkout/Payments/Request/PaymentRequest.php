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
     * Payment Context's unique identifier, from a payment context created with POST /payment-contexts.
     * Required when requesting a payment from a payment context.
     * [Optional]
     * ^(pct)_(\w{26})$
     * max 30 characters
     * min 30 characters
     * @var string
     */
    public $payment_context_id;

    /**
     * The source of the payment. You can encrypt this field by using the `Cko-Encryption-Key-Id` and
     * `Cko-Encrypted-Properties` headers.
     * [Optional]
     * @var AbstractRequestSource
     */
    public $source;

    /**
     * A card payment source. The fallback source to use if the primary source is unavailable.
     * [Optional]
     * @var AbstractRequestSource|null $fallback_source
     */
    public $fallback_source;

    /**
     * The payment amount. To perform a card verification, do not provide the amount or provide a value of `0`. The
     * amount must be provided in the minor currency unit.
     * [Optional]
     * Format: int64
     * min 0
     * max 9999999999
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO currency code
     * [Required]
     * max 3 characters
     * min 3 characters
     * @var string value of Currency
     */
    public $currency;

    /**
     * The type of payment. This field is required for card payments in which the cardholder is not present. For
     * example, mail orders, telephone orders, or merchant-initiated transactions (MITs) in a recurring payment
     * series. For MITs, this field must not be set to `Regular`.
     * [Optional]
     * Default: "Regular"
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * The details of a recurring subscription or installment
     * [Optional]
     * @var PaymentPlan|null $payment_plan
     */
    public $payment_plan;

    /**
     * Whether the payment is a merchant-initiated transaction (MIT). Must be set to `true` for all MITs. If you set
     * this field to `true`, the value for `payment_type` must not be set to `Regular`.
     * [Optional]
     * @var bool
     */
    public $merchant_initiated;

    /**
     * A reference you can use to identify the payment. For example, an order number. - For Amex payments, the
     * reference has a 30-character limit. - For Benefit payments, the reference must be a unique alphanumeric value.
     * - For Blik payments, the reference has a 35-character limit. - For Discover payments, the reference has a
     * 60-character limit. - For iDEAL payments, the reference is required and must be an alphanumeric value with a
     * 35-character limit. - For OmanNet payments, the reference must be a unique alphanumeric value between 12 and
     * 30 characters long, generated for every payment action. - For seQura payments, the reference is required and
     * must be a unique value. - For TWINT payments, the reference has a 50 character limit.
     * [Optional]
     * max 80 characters
     * @var string
     */
    public $reference;

    /**
     * A description of the payment.
     * [Optional]
     * max 100 characters
     * @var string
     */
    public $description;

    /**
     * Provides information required to authenticate payments.
     * [Optional]
     * @var Authentication
     */
    public $authentication;

    /**
     * The authorization type
     * [Optional]
     * Enum: "Final" "Estimated"
     * Default: "Final"
     * @var string value of AuthorizationType
     */
    public $authorization_type;

    /**
     * Required information to allow partial authorization
     * [Optional]
     * @var PartialAuthorization
     */
    public $partial_authorization;

    /**
     * Whether to capture the payment (if applicable)
     * [Optional]
     * Default: true
     * @var bool
     */
    public $capture;

    /**
     * A timestamp (ISO 8601 code) that determines when the payment should be captured. Providing this field will
     * automatically set `capture` to true
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var DateTime
     */
    public $capture_on;

    /**
     * The date and time when the Multibanco payment expires in UTC. Format – ISO 8601 Example–
     * `2025-01-31T10:20:30.456`
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var DateTime|null $expire_on
     */
    public $expire_on;

    /**
     * The customer's details. Required if `source.type` is `tamara`
     * [Optional]
     * @var CustomerRequest
     */
    public $customer;

    /**
     * An optional description that is displayed on the customer's statement identifying a purchase.
     * [Optional]
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * The shipping details.
     * [Optional]
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * The dimension details about business segment for payment request. At least one dimension required.
     * [Optional]
     * @var PaymentSegment
     */
    public $segment;

    /**
     * Information required for 3D Secure authentication payments.
     * [Optional]
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * The processing channel to be used for the payment
     * [Optional]
     * ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;

    /**
     * An identifier that links the payment to an existing series of payments. You must only pass this field if the
     * transaction is a merchant-initiated transaction (MIT) in a recurring payment series. To link the payment, pass
     * one of the following as its value: - a payment identifier (for example, `pay_cr4hxwizzp6k7biycuk2ibltnm`) from
     * the recurring series, or - the scheme transaction ID
     * [Optional]
     * max 100 characters
     * @var string
     */
    public $previous_payment_id;

    /**
     * Configures the risk assessment performed during the processing of the payment
     * [Optional]
     * @var RiskRequest
     */
    public $risk;

    /**
     * For redirect payment methods, this overrides the default success redirect URL configured on your account
     * [Optional]
     * Format: uri
     * max 1024 characters
     * @var string
     */
    public $success_url;

    /**
     * For redirect payment methods, this overrides the default failure redirect URL configured on your account
     * [Optional]
     * Format: uri
     * max 1024 characters
     * @var string
     */
    public $failure_url;

    /**
     * Use the `risk.device.network.ipv4` or `risk.device.network.ipv6` field instead. The IP address used to make
     * the payment. Used by Checkout.com's risk engine to check the customer's IP address – only accepts IPv4 and
     * IPv6 addresses. Required if `source.type` is `sequra`
     * [Optional]
     * Format: ipv4
     * max 45 characters
     * @deprecated
     * @var string
     * Obsolete - Use risk.device.network.ipv4 or risk.device.network.ipv6 instead
     */
    public $payment_ip;

    /**
     * Information about the sender of the payment's funds
     * [Optional]
     * @var PaymentSender
     */
    public $sender;

    /**
     * Information about the recipient of the payment's funds. Relevant for Account Funding Transactions and VISA or
     * Mastercard domestic UK transactions processed by financial institutions.
     * [Optional]
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * @deprecated This property will be removed in the future, and should be used {@link amount_allocations} instead
     * @var MarketplaceData
     */
    public $marketplace;

    /**
     * The sub-entities that the payment is being processed on behalf of
     * [Optional]
     * min 1 items
     * max 50 items
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * Use the processing object to influence or override the data sent during card processing
     * [Optional]
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * Stores additional information about a transaction with custom fields and up to five user-defined fields, which
     * you can use for reporting purposes. The object supports `string`, `number`, and `boolean` fields, but not
     * arrays or objects. You can provide up to 20 metadata fields per API call, but the value of each field must not
     * exceed 255 characters in length. You can also reference `metadata` properties in your custom rules for Fraud
     * Detection. For example, `$coupon_code = '1234’`.
     * [Optional]
     * @var array
     */
    public $metadata;

    /**
     * The order's line items.
     * [Optional]
     * max 998 items
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * Configuration of asynchronous retries. For more information about asynchronous retries and supported response
     * codes, see Configure scheduled retries.
     * [Optional]
     * @var PaymentRetryRequest|null $retry
     */
    public $retry;

    /**
     * Details about the payment instruction.
     * [Optional]
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * The details of the subscription.
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
