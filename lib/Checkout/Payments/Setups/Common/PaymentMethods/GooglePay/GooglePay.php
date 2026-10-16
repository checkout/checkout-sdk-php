<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\GooglePay;

use Checkout\Common\Phone;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\CardAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\WalletToken;

/**
 * The Google Pay payment method's details and configuration. Serialized under the key "googlepay".
 *
 * Inherits status and flags from PaymentSetupPaymentMethod. This payment method has no details object.
 */
class GooglePay extends PaymentSetupPaymentMethod
{
    /**
     * The Google Pay payment token data.
     * [Optional]
     * writeOnly
     * @var GooglePayTokenData
     */
    public $token_data;

    /**
     * The Checkout.com Google Pay token.
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
     * The list of supported card scheme names. Google Pay does not list cartes_bancaires.
     * [Optional]
     * readOnly
     * Enum: "accel" "amex" "china_union_pay" "diners" "discover" "jcb" "mada" "maestro" "mastercard" "nyce"
     * "omannet" "pulse" "shazam" "star" "upi" "visa"
     * @var string[] values of PaymentSetupCardScheme
     */
    public $schemes;

    /**
     * The preferred card scheme to process the payment.
     * [Optional]
     * Enum: "visa" "mastercard"
     * @var string value of PreferredSchema (visa or mastercard only)
     */
    public $preferred_scheme;
}
