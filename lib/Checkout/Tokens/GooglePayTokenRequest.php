<?php

namespace Checkout\Tokens;

class GooglePayTokenRequest extends WalletTokenRequest
{
    public function __construct()
    {
        parent::__construct(TokenType::$googlepay);
    }

    /**
     * @var GooglePayTokenData
     */
    public $token_data;
}
