<?php

namespace Checkout\Issuing\ControlGroups\Requests;

use Checkout\Issuing\ControlGroups\Entities\Control;
use Checkout\Issuing\ControlGroups\Entities\FailIf;

class CreateControlGroupRequest
{
    /**
     * The ID of the card or control profile to apply the control to.
     * Note that control profiles cannot be a target for velocity_limit controls. (Required)
     *
     * @var string
     */
    public $target_id;

    /**
     * Sets how to determine the result of the group. (Required)
     *
     * @var string value of FailIf
     */
    public $fail_if;

    /**
     * The controls that belong to the group. (Required)
     *
     * @var Control[]
     */
    public $controls;

    /**
     * A description for the control group.
     *
     * @var string
     */
    public $description;
}
