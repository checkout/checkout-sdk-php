<?php

namespace Checkout\Issuing\Disputes\Requests;

use Checkout\Issuing\Disputes\Entities\Evidence;

class SubmitDisputeRequest
{
    /**
     * The updated four-digit scheme-specific reason code.
     * If not provided, Checkout.com uses the existing reason code.
     *
     * @var string
     */
    public $reason;

    /**
     * Your evidence for the chargeback, if updated since you created the dispute.
     *
     * @var array of Evidence
     */
    public $evidence;

    /**
     * The updated disputed amount, in the minor unit of the transaction or representment currency.
     * If not provided, Checkout.com uses the existing disputed amount.
     *
     * @var int
     */
    public $amount;
}
