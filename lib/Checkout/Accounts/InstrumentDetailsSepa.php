<?php

namespace Checkout\Accounts;

class InstrumentDetailsSepa implements InstrumentDetails
{
    /**
     * @var string
     */
    public $iban;

    /**
     * @var string
     */
    public $swift_bic;
}
