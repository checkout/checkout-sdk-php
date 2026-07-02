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
     * @var string
     */
    public $processing_channel_id;

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
    public $payment_type = "Regular";

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * @var PaymentMethods
     */
    public $payment_methods;

    /**
     * @var Settings
     */
    public $settings;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var Order
     */
    public $order;

    /**
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
