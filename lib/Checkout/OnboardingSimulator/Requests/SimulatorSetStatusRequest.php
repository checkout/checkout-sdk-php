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
    private $status;

    // Getters and setters

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
