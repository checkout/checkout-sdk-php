<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\PayByBank;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The Pay by Bank (Open Banking) payment method's details and configuration. Serialized under the key
 * "paybybank".
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class PayByBank extends PaymentSetupPaymentMethod
{
    /**
     * The initialization state of the payment method.
     * [Optional]
     * @deprecated Not part of the PayByBank schema in the Payment Setups API specification; the API
     *     ignores it on requests. There is no replacement. Will be removed in the next major version.
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;

    /**
     * The identifier of the bank the customer has selected for the payment.
     * [Optional]
     * @var string
     */
    public $bank_id;

    /**
     * The next available action for the payment method.
     * [Optional]
     * readOnly
     * @var PayByBankAction
     */
    public $action;
}
