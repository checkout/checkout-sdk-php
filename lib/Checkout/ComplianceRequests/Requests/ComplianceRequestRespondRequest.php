<?php

namespace Checkout\ComplianceRequests\Requests;

use Checkout\ComplianceRequests\Entities\ComplianceRespondedFields;

/**
 * Represents the payload used to respond to a compliance request (POST /compliance-requests/{payment_id}).
 */
class ComplianceRequestRespondRequest
{
    /**
     * The response details grouped by sender and recipient.
     * [Required]
     *
     * @var ComplianceRespondedFields
     */
    public $fields;

    /**
     * Optional free-text comment provided with the response.
     * [Optional]
     * Nullable in the API schema.
     *
     * @var string|null
     */
    public $comments;
}
