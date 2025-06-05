<?php

namespace Checkout\Payments;

class DeviceDetails
{
    /**
     * The contents of the HTTP User-Agent request header (Optional, max 2048 characters)
     *
     * @var string|null
     */
    public $user_agent;

    /**
     * Details of the device network. Either ipv4 or ipv6 is required (Optional)
     *
     * @var Network|null
     */
    public $network;

    /**
     * Details of the device ID provider (Optional)
     *
     * @var DeviceProvider|null
     */
    public $provider;

    /**
     * UTC date and time the payment was performed, format – ISO 8601 (Optional)
     *
     * @var string|null
     */
    public $timestamp;

    /**
     * Time difference between UTC and local time in minutes (Optional, 1–5 characters)
     *
     * @var string|null
     */
    public $timezone;

    /**
     * Specifies if the device is running in a virtual machine (Optional)
     *
     * @var bool|null
     */
    public $virtual_machine;

    /**
     * Specifies if the browser is in incognito mode (Optional)
     *
     * @var bool|null
     */
    public $incognito;

    /**
     * Specifies if the device is jailbroken (Optional)
     *
     * @var bool|null
     */
    public $jailbroken;

    /**
     * Specifies if the device is rooted (Optional)
     *
     * @var bool|null
     */
    public $rooted;

    /**
     * Specifies if Java is enabled in the browser (Optional)
     *
     * @var bool|null
     */
    public $java_enabled;

    /**
     * Specifies if JavaScript is enabled in the browser (Optional)
     *
     * @var bool|null
     */
    public $javascript_enabled;

    /**
     * Browser language, format – IETF BCP47 language tag (Optional, 1–12 characters)
     *
     * @var string|null
     */
    public $language;

    /**
     * Bit depth of color palette in bits per pixel (Optional)
     *
     * @var string|null
     */
    public $color_depth;

    /**
     * Height of the device screen in pixels (Optional, 1–6 characters)
     *
     * @var string|null
     */
    public $screen_height;

    /**
     * Width of the device screen in pixels (Optional, 1–6 characters)
     *
     * @var string|null
     */
    public $screen_width;
}
