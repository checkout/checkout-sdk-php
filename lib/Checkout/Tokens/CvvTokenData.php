<?php

namespace Checkout\Tokens;

class CvvTokenData
{
    /**
     * The card verification value/code. 3 digits, except for American Express (4 digits).
     * [Required]
     * min 3 characters
     * max 4 characters
     * @var string $cvv
     */
    public $cvv;
}
