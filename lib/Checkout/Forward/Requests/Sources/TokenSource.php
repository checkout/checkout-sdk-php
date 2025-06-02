<?php

namespace Checkout\Forward\Requests\Sources;

class TokenSource extends AbstractSource
{
    /**
     * The unique Checkout.com token (Required, pattern ^(tok)_(\w{26})$)
     *
     * @var string
     */
    public $token;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(SourceType::$token);
    }
}
