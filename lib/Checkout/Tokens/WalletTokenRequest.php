<?php

namespace Checkout\Tokens;

abstract class WalletTokenRequest
{
    /**
     * @var string value of TokenType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
