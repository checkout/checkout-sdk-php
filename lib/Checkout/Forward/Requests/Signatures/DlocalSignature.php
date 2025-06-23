<?php

namespace Checkout\Forward\Requests\Signatures;

use Checkout\Forward\Requests\Sources\SourceType;

class DlocalSignature extends AbstractSignature
{
    /**
     * The parameters required to generate an HMAC signature for the dLocal API. See their documentation for details.
     * This method requires you to provide the X-Login header value in the destination request headers.
     * When used, the Forward API appends the X-Date and Authorization headers to the outgoing HTTP request before
     * forwarding.
     *
     * @var DlocalParameters
     */
    public $dlocal_parameters;

    /**
     * Initializes a new instance of the DlocalSignature class.
     */
    public function __construct()
    {
        parent::__construct(SignatureType::$dlocal);
    }
}
