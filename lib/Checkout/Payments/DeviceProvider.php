<?php

namespace Checkout\Payments;

class DeviceProvider
{
    /**
     * The unique identifier for the device (Optional)
     *
     * @var string|null
     */
    public $id;

    /**
     * The name of the provider that generated the device identifier (Optional)
     *
     * @var string|null
     */
    public $name;
}
