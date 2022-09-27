<?php

namespace Checkout\Payments\Request\Source\Apm;

class FawryProduct
{
    /**
     * @var string
     */
    public $product_id;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var int
     */
    public $price;

    /**
     * @var string
     */
    public $description;
}
