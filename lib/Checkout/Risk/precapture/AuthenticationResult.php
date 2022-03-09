<?php

namespace Checkout\Risk\precapture;

class AuthenticationResult
{
    public bool $attempted;

    public bool $challenged;

    public bool $succeeded;

    public bool $liability_shifted;

    public string $method;

    public string $version;
}
