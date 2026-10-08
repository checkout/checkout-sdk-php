<?php

namespace Checkout\Payments\Setups\Common\Customer;

/**
 * The type of client the customer uses to initiate the payment.
 */
class DeviceClient
{
    /**
     * A web browser on a desktop device.
     * @var string
     */
    public static $web = "web";

    /**
     * A web browser on a mobile device.
     * @var string
     */
    public static $mobile_web = "mobile_web";

    /**
     * A native mobile application.
     * @var string
     */
    public static $app = "app";
}
