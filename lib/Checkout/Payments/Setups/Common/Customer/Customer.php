<?php

namespace Checkout\Payments\Setups\Common\Customer;

use Checkout\Common\Phone;

class Customer
{
    /**
     * @var Email
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var Device
     */
    public $device;

    /**
     * @var MerchantAccount
     */
    public $merchant_account;
}
