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
     * @var PreferredSchema
     */
    public $preferred_scheme;

    /**
     * @var ProductType
     */
    public $product_type;

    /**
     * @var string
     */
    public $open_id;

    /**
     * @var int
     */
    public $original_order_amount;

    /**
     * @var string
     */
    public $receipt_id;

    /**
     * @var Aggregator
     */
    public $aggregator;

    /**
     * @var DLocalProcessingSettings
     */
    public $dlocal;
}
