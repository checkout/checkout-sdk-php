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
    private $fields;

    // Getters and setters

    /**
     * @return string[]
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * @param string[] $fields
     */
    public function setFields(?array $fields): void
    {
        $this->fields = $fields;
    }
}
