<?php

namespace Checkout\Payments\Setups\Request;

use Checkout\Payments\Setups\Common\Settings\Settings;
use Checkout\Payments\Setups\Common\Customer\Customer;
use Checkout\Payments\Setups\Common\Order\Order;
use Checkout\Payments\Setups\Common\Industry\Industry;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;

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
}
