<?php

namespace Checkout\Payments;

class RiskRequest
{
    /**
     * Whether a risk assessment should be performed (Optional)
     *
     * @var bool|null
     */
    public $enabled = true;

    /**
     * Device session ID collected from our standalone Risk.js package. If you integrate using our Frames
     * solution, this ID is not required (Optional, pattern ^(dsid)_(\w{26})$)
     *
     * @var string|null
     */
    public $device_session_id;

    /**
     * Details of the device from which the payment originated (Optional)
     *
     * @var DeviceDetails|null
     */
    public $device;
}
