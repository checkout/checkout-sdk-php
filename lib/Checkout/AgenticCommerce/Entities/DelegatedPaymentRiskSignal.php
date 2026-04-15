<?php

namespace Checkout\AgenticCommerce\Entities;

/**
 * A risk assessment signal provided by the platform to support fraud decisioning.
 */
class DelegatedPaymentRiskSignal
{
    /**
     * The type of risk signal.
     * [Required]
     *
     * @var string
     */
    public $type;

    /**
     * The risk score associated with this signal.
     * [Required]
     *
     * @var int
     */
    public $score;

    /**
     * The action taken based on the risk assessment.
     * [Required]
     *
     * @var string
     */
    public $action;
}
