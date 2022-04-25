<?php

namespace Checkout\Tokens;

class ApplePayTokenData
{
    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $data;

    /**
     * @var string
     */
    public $signature;

    /**
     * @var array
     */
    public $header;
}
