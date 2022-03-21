<?php

namespace Checkout\Tokens;

class ApplePayTokenRequest extends WalletTokenRequest
{
    function __construct()
    {
        parent::__construct(TokenType::$applepay);
    }

    // ApplePayTokenData
    public $token_data;

}
