<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CashApp;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

/**
 * The Cash App payment method's details and configuration.
 *
 * Inherits status, flags and initialization from PaymentMethodBase. Send initialization and
 * customer_profile_sharing; the remaining properties are returned by the API only.
 */
class CashApp extends PaymentMethodBase
{
    /**
     * Indicates whether the customer consents to share their Cash App customer profile with Checkout.com.
     * [Optional]
     * @var bool
     */
    public $customer_profile_sharing;

    /**
     * The customer's Cash App profile that they consented to share. Included in the response when
     * customer_profile_sharing is enabled.
     * Cash App releases this profile only once. It's present in the first successful response when you get
     * the Payment Setup after the customer authorizes the payment. Every subsequent response omits it.
     * [Optional]
     * readOnly
     * @var CashAppCustomerProfile
     */
    public $customer_profile;

    /**
     * A reference for the Cash App Pay transaction, returned by the provider.
     * [Optional]
     * readOnly
     * max 80 characters
     * @var string
     */
    public $reference;

    /**
     * The next available action for the payment method.
     * [Optional]
     * readOnly
     * @var CashAppAction
     */
    public $action;
}
