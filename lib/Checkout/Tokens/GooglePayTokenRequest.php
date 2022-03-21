<?php

namespace Checkout\Tokens;

class GooglePayTokenRequest extends WalletTokenRequest
{
    function __construct()
    {
        parent::__construct(TokenType::$googlepay);
    }

    // GooglePayTokenData
    public $token_data;

}
