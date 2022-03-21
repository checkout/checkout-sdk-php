<?php

namespace Checkout\Tokens;

abstract class WalletTokenRequest
{
    public $type;

    function __construct($type)
    {
        $this->type = $type;
    }
}
