<?php

namespace Checkout\Risk\PreCapture;

class AuthenticationResult
{
    public $attempted;

    public $challenged;

    public $succeeded;

    public $liability_shifted;

    public $method;

    public $version;
}
