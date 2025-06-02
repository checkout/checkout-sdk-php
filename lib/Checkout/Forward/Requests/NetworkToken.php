<?php

namespace Checkout\Forward\Requests;

class NetworkToken
{
    /**
     * Specifies whether to use a network token (Optional)
     *
     * @var bool|null
     */
    public $enabled;

    /**
     * Specifies whether to generate a cryptogram. For example, for customer-initiated transactions (CITs).
     * If you set network_token.enabled to true, you must provide this field (Optional)
     *
     * @var bool|null
     */
    public $request_cryptogram;
}
