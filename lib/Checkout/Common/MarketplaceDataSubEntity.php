<?php

namespace Checkout\Common;

class MarketplaceDataSubEntity
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
     * @var MarketplaceCommission
     */
    public $commission;
}
