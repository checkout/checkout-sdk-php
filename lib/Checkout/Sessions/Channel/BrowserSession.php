<?php

namespace Checkout\Sessions\Channel;

class BrowserSession extends ChannelData
{

    public function __construct()
    {
        parent::__construct(ChannelType::$browser);
    }

    public string $three_ds_method_completion;

    public string $accept_header;

    public bool $java_enabled;

    public string $language;

    public string $color_depth;

    public string $screen_height;

    public string $screen_width;

    public string $timezone;

    public string $user_agent;

    public string $ip_address;

}
