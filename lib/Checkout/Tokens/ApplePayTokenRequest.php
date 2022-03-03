<?php

namespace Checkout\Tokens;

class ApplePayTokenRequest extends WalletTokenRequest
{
    function __construct()
    {
        parent::__construct(TokenType::$applepay);
    }

    public ApplePayTokenData $token_data;

}
