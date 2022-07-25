<?php

namespace Checkout\Apm\Previous\Klarna;

class Klarna
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var array of KlarnaProduct
     */
    public $products;

    /**
     * @var array of KlarnaShippingInfo
     */
    public $shipping_info;

    /**
     * @var int
     */
    public $shipping_delay;
}
