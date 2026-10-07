<?php

namespace Checkout\Payments\Setups\Common\Customer;

use Checkout\Common\Phone;

class Customer
{
    /**
     * Details of the customer's email.
     * [Optional]
     * @var Email
     */
    public $email;

    /**
     * The customer's full name.
     * [Optional]
     * max 100 characters
     * @var string
     */
    public $name;

    /**
     * The customer's phone number.
     * [Optional]
     * @var Phone
     */
    public $phone;

    /**
     * Details of the customer's device.
     * [Optional]
     * @var Device
     */
    public $device;

    /**
     * Details of the account the customer holds with the merchant.
     * [Optional]
     * @var MerchantAccount
     */
    public $merchant_account;

    /**
     * The unique identifier of the customer.
     * [Optional]
     * @var string
     */
    public $id;

    /**
     * The two-letter ISO country code of the customer for this payment.
     * [Optional]
     * min 2 characters, max 2 characters
     * @var string value of Country
     */
    public $country;

    /**
     * The customer's tax identification number.
     * [Optional]
     * @var string
     */
    public $tax_number;
}
