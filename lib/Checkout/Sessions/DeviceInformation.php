<?php

namespace Checkout\Sessions;

class DeviceInformation
{
    /**
     * The unique identifier for the device.
     * [Optional]
     * @var string|null $device_id
     */
    public $device_id;

    /**
     * Device session ID collected from our standalone Risk.js package.
     * [Optional]
     * Pattern: ^(dsid)_(\w{26})$
     * @var string|null $device_session_id
     */
    public $device_session_id;
}
