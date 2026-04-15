<?php

namespace Checkout\ComplianceRequests\Entities;

/**
 * Describes a single response field provided for a compliance request.
 */
class ComplianceRespondedField
{
    /**
     * The field name being responded to.
     * [Required] (property must be present; value may be null per schema)
     *
     * @var string|null
     */
    public $name;

    /**
     * The value provided for the field.
     * [Required] (property must be present; value may be null per schema)
     *
     * @var mixed|null
     */
    public $value;

    /**
     * Indicates whether the value is unavailable for this field.
     * [Required]
     *
     * @var bool
     */
    public $not_available;
}
