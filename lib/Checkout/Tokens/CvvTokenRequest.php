<?php

namespace Checkout\Tokens;

class CvvTokenRequest extends WalletTokenRequest
{
    /**
     * Data containing the CVV token value.
     * [Required]
     * @var CvvTokenData $token_data
     */
    public $token_data;

    public function __construct()
    {
        parent::__construct(TokenType::$cvv);
    }
}
