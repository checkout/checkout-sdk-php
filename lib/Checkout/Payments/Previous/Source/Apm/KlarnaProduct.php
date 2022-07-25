<?php

namespace Checkout\Payments\Previous\Source\Apm;

class KlarnaProduct
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var int
     */
    public $unit_price;

    /**
     * @var int
     */
    public $tax_rate;

    /**
     * @var int
     */
    public $total_amount;

    /**
     * @var int
     */
    public $total_tax_amount;
}
