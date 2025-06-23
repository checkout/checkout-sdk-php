<?php

namespace Checkout\Forward\Requests\Signatures;

class DlocalParameters
{
    /**
     * The secret key used to generate the request signature. This is part of the dLocal API credentials.
     *
     * @var string
     */
    public $secret_key;
}
