<?php

namespace Checkout\Forward\Requests;

class DestinationRequest
{
    /**
     * The URL to forward the request to (Required, max 1024 characters)
     *
     * @var string
     */
    public $url;

    /**
     * The HTTP method to use for the forward request (Required)
     *
     * @var MethodType
     */
    public $method;

    /**
     * The HTTP headers to include in the forward request (Required)
     *
     * @var Headers
     */
    public $headers;

    /**
     * The HTTP message body to include in the forward request. If you provide source.id or source.token, you can
     * specify placeholder values in the body. The request will be enriched with the respective payment details from
     * the token or payment instrument you specified. For example, {{card_number}} (Required, max 16384 characters)
     *
     * @var string
     */
    public $body;
}
