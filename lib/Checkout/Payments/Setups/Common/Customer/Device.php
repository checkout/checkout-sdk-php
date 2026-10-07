<?php

namespace Checkout\Payments\Setups\Common\Customer;

class Device
{
    /**
     * The locale of the device.
     * [Optional]
     * @var string
     */
    public $locale;

    /**
     * A unique identifier for the customer's device.
     * [Optional]
     * @var string
     */
    public $fingerprint;

    /**
     * The customer's device IPv4 address, used by some payment methods for risk and eligibility checks.
     * [Optional]
     * @var string
     */
    public $ipv4;

    /**
     * The customer's device IPv6 address, used by some payment methods for risk and eligibility checks.
     * [Optional]
     * @var string
     */
    public $ipv6;

    /**
     * The type of client the customer uses to initiate the payment. Required when using Cash App Pay.
     * [Optional]
     * Enum: "web" "mobile_web" "app"
     * @var string value of DeviceClient
     */
    public $client;

    /**
     * The operating system of the customer's device.
     * [Optional]
     * Enum: "android" "ios"
     * @var string value of DeviceOs
     */
    public $os;
}
