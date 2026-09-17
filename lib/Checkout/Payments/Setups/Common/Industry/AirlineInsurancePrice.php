<?php

namespace Checkout\Payments\Setups\Common\Industry;

class AirlineInsurancePrice
{
    /**
     * The insurance price amount, in the minor currency unit.
     * [Optional]
     * @var float
     */
    public $amount;

    /**
     * The currency of the insurance price, as a three-letter ISO currency code.
     * [Optional]
     * @var string
     */
    public $currency;
}
