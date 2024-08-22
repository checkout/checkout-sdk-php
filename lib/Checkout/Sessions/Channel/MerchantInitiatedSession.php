<?php

namespace Checkout\Sessions\Channel;

final class MerchantInitiatedSession extends ChannelData
{
    public function __construct()
    {
        parent::__construct(ChannelType::$merchant_initiated);
    }

    /**
     * @var string value of RequestType
     */
    public $request_type;
}
