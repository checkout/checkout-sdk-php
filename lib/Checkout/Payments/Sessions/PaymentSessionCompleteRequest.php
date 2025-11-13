<?php

namespace Checkout\Payments\Sessions;

use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\PaymentCustomerRequest;
use Checkout\Payments\PaymentInstruction;
use DateTime;

class PaymentSessionCompleteRequest
{
    /**
     * A unique token representing the additional customer data captured by Flow.
     * Do not log or store this value.
     * @var string
     */
    public $session_data;

    /**
     * The payment amount. Provide a value of 0 to perform a card verification.
     * The amount must be provided in the minor currency unit.
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO currency code.
     * @var string value of Currency
     */
    public $currency;

    /**
     * The billing information for the payment.
     * @var BillingInformation
     */
    public $billing;

    /**
     * For redirect payment methods, this overrides the default success redirect URL configured on your account.
     * @var string
     */
    public $success_url;

    /**
     * For redirect payment methods, this overrides the default failure redirect URL configured on your account.
     * @var string
     */
    public $failure_url;

    /**
     * Must be specified for card-not-present (CNP) payments.
     * Values: Regular, Recurring, MOTO, Installment, Unscheduled
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * An optional descriptor displayed on the customer's statement.
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * A reference you can use to identify the payment. For example, an order number.
     * @var string
     */
    public $reference;

    /**
     * A description of the payment.
     * @var string
     */
    public $description;

    /**
     * Details of the customer associated with the payment.
     * @var PaymentCustomerRequest
     */
    public $customer;

    /**
     * The shipping details for the payment.
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * Information about the recipient of the payment's funds.
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * Use the processing object to influence or override the data sent during card processing.
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * Contains the purpose of payment for account funding transactions (AFT).
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * The processing channel to use for the payment.
     * @var string
     */
    public $processing_channel_id;

    /**
     * Configurations for payment method-specific settings.
     * @var PaymentMethodConfiguration
     */
    public $payment_method_configuration;

    /**
     * The line items in the order.
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * Specifies how to allocate the payment amount among one or more sub-entities.
     * @var array values of AmountAllocations
     */
    public $amount_allocations;

    /**
     * Configures the risk assessment performed during payment processing.
     * @var RiskRequest
     */
    public $risk;

    /**
     * For PayPal payments, this value overrides the PayPal account configuration.
     * @var string
     */
    public $display_name;

    /**
     * A set of key-value pairs that you can attach to a payment. It can be useful for storing additional information in a structured format.
     * @var array
     */
    public $metadata;

    /**
     * Creates a payment session localized for the specified country and language combination.
     * @var string
     */
    public $locale;

    /**
     * Information required for 3D Secure authentication payments.
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * Information about the sender of the payment's funds.
     * @var PaymentSender
     */
    public $sender;

    /**
     * Specifies whether to capture the payment, if applicable.
     * @var bool
     */
    public $capture;

    /**
     * A timestamp specifying when to capture the payment, as an ISO 8601 code.
     * If a value is provided, capture is automatically set to true.
     * @var DateTime
     */
    public $capture_on;
}
