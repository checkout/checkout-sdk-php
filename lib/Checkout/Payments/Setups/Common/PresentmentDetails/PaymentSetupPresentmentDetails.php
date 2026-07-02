<?php

namespace Checkout\Payments\Setups\Common\PresentmentDetails;

class PaymentSetupPresentmentDetails
{
    /**
     * The presentment amount, in the minor currency unit.
     * [Optional]
     * @var int
     */
    public $amount;

    /**
     * The presentment currency, as a three-letter ISO currency code.
     * [Optional]
     * @var string value of Currency
     */
    public $currency;
}
