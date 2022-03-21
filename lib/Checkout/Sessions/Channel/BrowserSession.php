<?php

namespace Checkout\Sessions\Channel;

class BrowserSession extends ChannelData
{

    public function __construct()
    {
        parent::__construct(ChannelType::$browser);
    }

    public $three_ds_method_completion;

    public $accept_header;

    public $java_enabled;

    public $language;

    public $color_depth;

    public $screen_height;

    public $screen_width;

    public $timezone;

    public $user_agent;

    public $ip_address;

}
