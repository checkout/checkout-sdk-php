<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\ApplePay;

use Checkout\Common\Phone;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\CardAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\WalletToken;

/**
 * The Apple Pay payment method's details and configuration. Serialized under the key "applepay".
 *
 * Inherits status and flags from PaymentSetupPaymentMethod. This payment method has no details object.
 */
class ApplePay extends PaymentSetupPaymentMethod
{
    /**
     * The Apple Pay payment token data.
     * [Optional]
     * writeOnly
     * @var ApplePayTokenData
     */
    public $token_data;

    /**
     * The Checkout.com Apple Pay token.
     * [Optional]
     * @var WalletToken
     */
    public $token;

    /**
     * Set to true if you intend to reuse the payment credentials in subsequent payments. If set to false,
     * a payment instrument will not be included in the payment response.
     * [Optional]
     * writeOnly
     * @var bool
     */
    public $store_for_future_use;

    /**
     * The customer's phone number. The country_code is min 1, max 7 characters; the number is min 6,
     * max 25 characters.
     * [Optional]
     * @var Phone
     */
    public $phone;

    /**
     * Account holder details for the payment.
     * [Optional]
     * @var CardAccountHolder
     */
    public $account_holder;

    /**
     * The list of supported card scheme names.
     * [Optional]
     * readOnly
     * Enum: "accel" "amex" "cartes_bancaires" "china_union_pay" "diners" "discover" "jcb" "mada" "maestro"
     * "mastercard" "nyce" "omannet" "pulse" "shazam" "star" "upi" "visa"
     * @var string[] values of PaymentSetupCardScheme
     */
    public $schemes;

    /**
     * The preferred card scheme to process the payment.
     * [Optional]
     * Enum: "visa" "mastercard" "cartes_bancaires"
     * @var string value of PreferredSchema
     */
    public $preferred_scheme;
}
