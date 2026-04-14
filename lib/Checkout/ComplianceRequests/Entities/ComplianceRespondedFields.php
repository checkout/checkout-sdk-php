<?php

namespace Checkout\ComplianceRequests\Entities;

/**
 * Groups the responded fields by party (sender/recipient).
 */
class ComplianceRespondedFields
{
    /**
     * The fields provided for the sender.
     * [Optional]
     * Nullable in the API schema.
     *
     * @var ComplianceRespondedField[]|null
     */
    public $sender;

    /**
     * The fields provided for the recipient.
     * [Optional]
     * Nullable in the API schema.
     *
     * @var ComplianceRespondedField[]|null
     */
    public $recipient;
}
