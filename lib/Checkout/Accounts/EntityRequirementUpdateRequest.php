<?php

namespace Checkout\Accounts;

/**
 * Request body used to resolve a requirement.
 */
class EntityRequirementUpdateRequest
{
    /**
     * @var mixed
     * [Required]
     * The response to the requirement. The expected shape depends on the requirement
     * and is defined by the JSON Schema returned in the requirement details response.
     * Common shapes include a file reference, a primitive value, or a structured object.
     */
    private $value;

    // Getters and setters

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
