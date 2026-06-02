<?php

namespace Checkout\OnboardingSimulator\Requests;

/**
 * Request to set requirements as due for an entity.
 */
class SimulatorSetRequirementsDueRequest
{
    /**
     * @var string[]
     * [Required]
     * The requirement fields to mark as due.
     */
    public $fields;
}
