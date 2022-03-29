<?php

namespace Checkout\Tokens;

abstract class WalletTokenRequest
{
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
