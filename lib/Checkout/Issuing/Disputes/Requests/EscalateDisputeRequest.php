<?php

namespace Checkout\Issuing\Disputes\Requests;

use Checkout\Issuing\Disputes\Entities\Evidence;
use Checkout\Issuing\Disputes\Entities\ReasonChange;
use Checkout\Issuing\Disputes\Entities\IssuingDisputeFraudDetails;

class EscalateDisputeRequest
{
    /**
     * Justification for escalating the dispute.
     * [Required]
     * max 13000 characters
     * @var string
     */
    public $justification;

    /**
     * Your evidence for escalating the dispute, in line with the card scheme's requirements.
     * If the request goes to arbitration, the card scheme ignores any evidence you provide at this stage using this request.
     * [Optional]
     * @var array of Evidence
     */
    public $additional_evidence;

    /**
     * The updated disputed amount, in the minor unit of the representment currency.
     * [Optional]
     * @var int
     */
    public $amount;

    /**
     * The change to the dispute reason and your justification for changing it.
     * [Optional]
     * @var ReasonChange
     */
    public $reason_change;

    /**
     * Provides fraud-related details. This field is required if the dispute has a fraud-related reason code
     * at the escalation stage, or after a requested reason code change to a fraud code.
     * [Optional]
     * @var IssuingDisputeFraudDetails
     */
    public $fraud_details;
}
