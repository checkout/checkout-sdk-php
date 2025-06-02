<?php

namespace Checkout\Forward\Requests;

use Checkout\Forward\Requests\Sources\AbstractSource;

class ForwardRequest
{
    /**
     * The payment source to enrich the forward request with. You can provide placeholder values in
     * destination_request.body. The request will be enriched with the respective payment credentials from the token or
     * payment instrument you specified. For example, {{card_number}} (Required)
     *
     * @var AbstractSource
     */
    public $source;

    /**
     * The parameters of the forward request (Required)
     *
     * @var DestinationRequest
     */
    public $destination_request;

    /**
     * The unique reference for the forward request (Optional, max 80 characters)
     *
     * @var string|null
     */
    public $reference;

    /**
     * The processing channel ID to associate the billing for the forward request with (Optional,
     * pattern ^(pc)_(\w{26})$)
     *
     * @var string|null
     */
    public $processing_channel_id;

    /**
     * Specifies if and how a network token should be used in the forward request (Optional)
     *
     * @var NetworkToken|null
     */
    public $network_token;
}
