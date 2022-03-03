<?php

namespace Checkout\Tokens;

class ApplePayTokenData
{
    public string $version;

    public string $data;

    public string $signature;

    public array $header;
}
