<?php

namespace Checkout\Payments;

class ProcessingSettings
{
    /**
     * @var bool
     */
    public $aft;

    /**
     * @var int
     */
    public $tax_amount;

    /**
     * @var int
     */
    public $shipping_amount;

    /**
     * @var Aggregator
     */
    public $aggregator;

    /**
     * @var DLocalProcessingSettings
     */
    public $dlocal;
}
