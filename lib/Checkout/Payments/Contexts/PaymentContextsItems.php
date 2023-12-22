<?php

namespace Checkout\Payments\Contexts;

class PaymentContextsItems
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
     * @var string
     */
    public $reference;

    /**
     * @var int
     */
    public $total_amount;

    /**
     * @var int
     */
    public $tax_amount;

    /**
     * @var int
     */
    public $discount_amount;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $image_url;
}
