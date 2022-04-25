<?php

namespace Checkout\Tokens;

abstract class WalletTokenRequest
{
    /**
     * @var TokenType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
