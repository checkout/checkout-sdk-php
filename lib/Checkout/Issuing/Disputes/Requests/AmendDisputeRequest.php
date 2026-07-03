<?php

namespace Checkout\Issuing\Disputes\Requests;

use Checkout\Issuing\Disputes\Entities\Evidence;
use Checkout\Issuing\Disputes\Entities\IssuingDisputeFraudDetails;

class AmendDisputeRequest
{
    /**
     * The updated four-digit scheme-specific reason code.
     * If a value is not provided, the existing reason code is retained.
     * [Optional]
     * @var string
     */
    public $reason;

    /**
     * The updated disputed amount, in the minor unit of the transaction currency.
     * If not provided, the existing amount is retained.
     * [Optional]
     * @var int
     */
    public $amount;

    /**
     * The updated or additional evidence requested by the Dispute Resolution team.
     * Follow the card scheme's requirements.
     * [Optional]
     * @var array of Evidence
     */
    public $evidence;

    /**
     * Provides the fraud category, and additional context if available.
     * This field is required if reason specifies a fraud-related dispute.
     * [Optional]
     * @var IssuingDisputeFraudDetails
     */
    public $fraud_details;

    /**
     * Explains the justification for the reason change. This is shared with the Dispute Resolution review
     * team and may be submitted to the card scheme.
     * This field is required if you change the reason at the escalation stage.
     * [Optional]
     * max 13000 characters
     * @var string
     */
    public $reason_change_justification;

    /**
     * Free-form text that you can use to explain your choices, provide additional context, or ask questions
     * about the requested changes.
     * [Optional]
     * max 1000 characters
     * @var string
     */
    public $action_response;
}
