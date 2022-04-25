<?php

namespace Checkout\Marketplace;

use Checkout\Common\Currency;

class Profile
{
    /**
     * @var array
     */
    public $urls;

    /**
     * @var array
     */
    public $mccs;

    /**
     * @var Currency
     */
    public $default_holding_currency;
}
