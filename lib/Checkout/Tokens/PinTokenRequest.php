<?php

namespace Checkout\Tokens;

class PinTokenRequest extends WalletTokenRequest
{
    /**
     * Data containing the PIN token value.
     * [Required]
     * @var PinTokenData $token_data
     */
    public $token_data;

    public function __construct()
    {
        parent::__construct(TokenType::$pin);
    }
}
