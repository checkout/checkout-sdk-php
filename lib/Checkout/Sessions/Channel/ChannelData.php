<?php

namespace Checkout\Sessions\Channel;

abstract class ChannelData
{
    public function __construct(string $channel)
    {
        $this->channel = $channel;
    }

    public string $channel;

}
