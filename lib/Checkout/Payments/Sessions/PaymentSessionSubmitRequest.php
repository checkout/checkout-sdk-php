<?php

namespace Checkout\Payments\Sessions;

use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\PaymentCustomerRequest;
use Checkout\Payments\PaymentInstruction;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Sender\PaymentSender;
use Checkout\Payments\ShippingDetails;
use DateTime;

class PaymentSessionSubmitRequest
{
    /**
     * A unique token representing the additional customer data captured by Flow.
     * @var string
     */
    public $session_data;

    /**
     * The payment amount in minor currency units. Provide 0 for card verification.
     * @var int
     */
    public $amount;

    /**
     * A reference to identify the payment (e.g., order number).
     * @var string
     */
    public $reference;

    /**
     * The line items in the order.
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * Information required for 3D Secure authentication payments.
     * @var ThreeDsRequest
     */
    public $three_ds;

    /**
     * The customer's IP address. Only IPv4 and IPv6 addresses are accepted.
     * @var string
     */
    public $ip_address;

    /**
     * Must be specified for card-not-present (CNP) payments.
     * Values: Regular, Recurring, MOTO, Installment, Unscheduled
     * @var string value of PaymentType
     */
    public $payment_type;

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
     * The billing descriptor for the payment.
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * Specifies whether to capture the payment, if applicable.
     * @var bool
     */
    public $capture;

    /**
     * A timestamp specifying when to capture the payment, as an ISO 8601 code.
     * @var DateTime
     */
    public $capture_on;

    /**
     * The customer details for the payment.
     * @var PaymentCustomerRequest
     */
    public $customer;

    /**
     * For redirect payment methods, this overrides the default failure redirect URL.
     * @var string
     */
    public $failure_url;

    /**
     * Contains the purpose of payment for account funding transactions.
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * A set of key-value pairs that you can attach to a payment.
     * @var array
     */
    public $metadata;

    /**
     * Configurations for payment method-specific settings.
     * @var PaymentMethodConfiguration
     */
    public $payment_method_configuration;

    /**
     * Use the processing object to influence or override the data sent during card processing.
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * The processing channel to use for the payment.
     * @var string
     */
    public $processing_channel_id;

    /**
     * Information about the recipient of the payment's funds.
     * @var PaymentRecipient
     */
    public $recipient;

    /**
     * Information about the sender of the payment's funds.
     * @var PaymentSender
     */
    public $sender;

    /**
     * The shipping details for the payment.
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * For redirect payment methods, this overrides the default success redirect URL.
     * @var string
     */
    public $success_url;
}
