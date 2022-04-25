<?php

namespace Checkout\Tokens;

class ApplePayTokenRequest extends WalletTokenRequest
{
    public function __construct()
    {
        parent::__construct(TokenType::$applepay);
    }

    /**
     * @var ApplePayTokenData
     */
    public $token_data;
}
