<?php

namespace Checkout\Tokens;

class GooglePayTokenData
{
    public string $signature;

    public string $protocolVersion;

    public string $signedMessage;
}
