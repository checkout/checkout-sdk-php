<?php

namespace Checkout\Common;

class AmountAllocations
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var Commission
     */
    public $commission;
}
