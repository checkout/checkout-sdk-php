<?php

namespace Checkout\Tokens;

abstract class WalletTokenRequest
{
    public string $type;

    function __construct(string $type)
    {
        $this->type = $type;
    }
}
