<?php

namespace Checkout\Issuing\ControlProfiles\Requests;

class CreateControlProfileRequest
{
    /**
     * Control profile name. (Required)
     *
     * @var string
     */
    public $name;

    /**
     * Control profile description. (Optional)
     *
     * @var string
     */
    public $description;
}
