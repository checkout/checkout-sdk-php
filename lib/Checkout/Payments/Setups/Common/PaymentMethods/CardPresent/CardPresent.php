<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CardPresent;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

class CardPresent extends PaymentMethodBase
{
    /**
     * The Track 2 data read from card or device.
     * [Optional] writeOnly
     * @var string
     */
    public $track2;

    /**
     * The EMV data read from the card or device.
     * [Optional] writeOnly
     * @var string
     */
    public $emv;

    /**
     * The mode used to capture the card details at the point of sale.
     * [Optional] writeOnly
     * @var string
     */
    public $entry_mode;

    /**
     * The encrypted PIN block details.
     * [Optional] writeOnly
     * @var CardPresentPin
     */
    public $pin;

    /**
     * Set to true if you intend to reuse the payment credentials in subsequent payments.
     * [Optional] writeOnly
     * @var bool
     */
    public $store_for_future_use;

    /**
     * The cardholder's name.
     * [Optional] writeOnly
     * @var string
     */
    public $name;
}
