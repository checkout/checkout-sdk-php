<?php

namespace Checkout\Payments\Setups\Common\Order;

class PaymentSetupAmountAllocation
{
    /**
     * The id of the sub-entity.
     * [Required]
     * @var string
     */
    public $id;

    /**
     * The split amount - this will be credited to your sub-entity's currency account. The sum of all split
     * amounts must be equal to the payment amount. Provided in the minor currency unit.
     * [Required]
     * min 0
     * max 9999999999
     * @var int
     */
    public $amount;

    /**
     * A reference you can later use to identify this split, such as an order number.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $reference;

    /**
     * Commission you'd like to collect from this split.
     * [Optional]
     * @var AmountAllocationCommission
     */
    public $commission;
}
