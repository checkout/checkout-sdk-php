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
     * @deprecated Not in the request schema of any payout or unreferenced refund source; still returned in
     * PayoutDetails (source.amount). Retained for backwards compatibility and will be removed in the next
     * major version. There is no replacement.
     * @var int
     */
    public $amount;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
