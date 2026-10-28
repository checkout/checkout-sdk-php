<?php

namespace Checkout\Payments\Request\Source;

/**
 * The source of a card payout, bank payout or unreferenced refund.
 */
class PayoutRequestSource
{
    /**
     * The payout source type. Set by each subclass.
     * [Required]
     * @var string value of PayoutSourceType
     */
    public $type;

    /**
     * The amount of the source.
     * [Optional]
     *
     * @deprecated Not in the current specification of any payout or unreferenced refund source, so the
     * API does not define it. Retained for backwards compatibility and will be removed in a future
     * version.
     * @var int
     */
    public $amount;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
