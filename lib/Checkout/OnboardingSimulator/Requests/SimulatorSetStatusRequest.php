<?php

namespace Checkout\OnboardingSimulator\Requests;

/**
 * Request to set entity status.
 */
class SimulatorSetStatusRequest
{
    /**
     * @var string
     * [Required]
     * The status to set on the entity.
     * Enum: "draft" "requirements_due" "pending" "active" "restricted" "rejected" "inactive"
     */
    public $status;
}
