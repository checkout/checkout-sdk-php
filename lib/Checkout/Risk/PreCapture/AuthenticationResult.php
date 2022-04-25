<?php

namespace Checkout\Risk\PreCapture;

class AuthenticationResult
{
    /**
     * @var bool
     */
    public $attempted;

    /**
     * @var bool
     */
    public $challenged;

    /**
     * @var bool
     */
    public $succeeded;

    /**
     * @var bool
     */
    public $liability_shifted;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $version;
}
