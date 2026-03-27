<?php

namespace Checkout\Payments\ApplePay\Request;

class SigningRequest
{
    /**
     * The protocol version of the encryption type used.
     * Available values: "ec_v1", "rsa_v1", use ProtocolVersions class for reference.
     * Default: "ec_v1"
     *
     * @var string
     */
    public $protocol_version;
}
