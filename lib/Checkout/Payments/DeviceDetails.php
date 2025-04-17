<?php

namespace Checkout\Payments;

class DeviceDetails
{
    /**
     * @var string
     */
    public $user_agent;

    /**
     * @var Network
     */
    public $network;

    /**
     * @var DeviceProvider
     */
    public $provider;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var bool
     */
    public $virtual_machine;

    /**
     * @var bool
     */
    public $incognito;

    /**
     * @var bool
     */
    public $jailbroken;

    /**
     * @var bool
     */
    public $rooted;

    /**
     * @var bool
     */
    public $java_enabled;

    /**
     * @var bool
     */
    public $javascript_enabled;

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
}
