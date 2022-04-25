<?php

namespace Checkout\Sessions\Channel;

class BrowserSession extends ChannelData
{

    public function __construct()
    {
        parent::__construct(ChannelType::$browser);
    }

    /**
     * @var ThreeDsMethodCompletion
     */
    public $three_ds_method_completion;

    /**
     * @var string
     */
    public $accept_header;

    /**
     * @var bool
     */
    public $java_enabled;

    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $color_depth;

    /**
     * @var string
     */
    public $screen_height;

    /**
     * @var string
     */
    public $screen_width;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var string
     */
    public $user_agent;

    /**
     * @var string
     */
    public $ip_address;
}
