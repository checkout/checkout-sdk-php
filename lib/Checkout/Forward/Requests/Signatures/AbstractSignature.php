<?php

namespace Checkout\Forward\Requests\Signatures;

class AbstractSignature
{
    /**
     * The identifier of the supported signature generation method or a specific third-party service. (Required)
     *
     * @var string value of SignatureType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
