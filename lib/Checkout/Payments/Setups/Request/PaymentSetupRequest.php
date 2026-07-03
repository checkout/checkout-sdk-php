<?php

namespace Checkout\Payments\Setups\Request;

use Checkout\Payments\Setups\Common\Settings\Settings;
use Checkout\Payments\Setups\Common\Customer\Customer;
use Checkout\Payments\Setups\Common\Order\Order;
use Checkout\Payments\Setups\Common\Industry\Industry;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\BillingDescriptor\PaymentSetupBillingDescriptor;
use Checkout\Payments\Setups\Common\PresentmentDetails\PaymentSetupPresentmentDetails;
use Checkout\Payments\Setups\Common\Terminal\PaymentSetupTerminal;

class PaymentSetupRequest
{
    /**
     * The processing channel to use for the payment.
     * [Required]
     * ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;

    /**
     * The payment amount, in the minor currency unit.
     * [Required]
     * @var int
     */
    public $amount;

    /**
     * The currency of the payment, as a three-letter ISO currency code.
     * [Required]
     * @var string value of Currency
     */
    public $currency;

    /**
     * The type of payment.
     * [Optional]
     * @var string value of PaymentType
     */
    public $payment_type = "Regular";

    /**
     * A reference you can use to identify the payment. For example, an order number.
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
     * The payment methods that are enabled on your account and available for use.
     * [Optional]
     * @var PaymentMethods
     */
    public $payment_methods;

    /**
     * Settings for the Payment Setup.
     * [Optional]
     * @var Settings
     */
    public $settings;

    /**
     * The customer's details.
     * [Optional]
     * @var Customer
     */
    public $customer;

    /**
     * The customer's order details.
     * [Optional]
     * @var Order
     */
    public $order;

    /**
     * Industry-specific information.
     * [Optional]
     * @var Industry
     */
    public $industry;

    /**
     * The billing descriptor for the payment.
     * [Optional]
     * @var PaymentSetupBillingDescriptor
     */
    public $billing_descriptor;

    /**
     * The amount and currency to present to the customer, when the settlement currency differs from the
     * customer-facing currency.
     * [Optional]
     * @var PaymentSetupPresentmentDetails
     */
    public $presentment_details;

    /**
     * Terminal details.
     * [Optional]
     * @var PaymentSetupTerminal
     */
    public $terminal;
}
