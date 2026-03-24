<?php

namespace Checkout\Issuing\Disputes\Requests;

use Checkout\Issuing\Disputes\Entities\Evidence;

class CreateDisputeRequest
{
    /**
     * The transaction's unique identifier. (Required)
     *
     * @var string
     */
    public $transaction_id;

    /**
     * The four-digit scheme-specific reason code for the chargeback.
     * Only provide this if Checkout.com is your issuing processor.
     * Checkout.com does not validate this value. (Required)
     *
     * @var string
     */
    public $reason;

    /**
     * Your evidence for raising the chargeback, in line with the card scheme's requirements.
     *
     * @var array of Evidence
     */
    public $evidence;

    /**
     * The chargeback amount, in the minor unit of the transaction currency.
     * If not provided, Checkout.com uses the full amount of the presentment.
     *
     * @var int
     */
    public $amount;

    /**
     * The unique identifier for the disputed presentment message, if the transaction has multiple presentments.
     * If the transaction has only one presentment, Checkout.com uses this automatically.
     *
     * @var string
     */
    public $presentment_message_id;

    /**
     * Indicates whether to submit the dispute:
     * • Immediately – Set to true.
     * • Later – Set to false.
     * Default: false
     *
     * @var bool
     */
    public $is_ready_for_submission;

    /**
     * Your justification for the chargeback.
     *
     * @var string
     */
    public $justification;
}
