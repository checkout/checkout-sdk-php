<?php

namespace Checkout\Issuing\ControlProfiles\Requests;

class UpdateControlProfileRequest
{
    /**
     * The control profile name. (Required)
     *
     * @var string
     */
    public $name;

    /**
     * The control profile description. (Optional)
     *
     * @var string
     */
    public $description;
}
