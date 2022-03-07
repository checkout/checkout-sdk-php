<?php

namespace Checkout\Apm\Klarna;

class Klarna
{
    public string $description;

    //KlarnaProduct
    public array $products;

    //KlarnaShippingInfo
    public array $shipping_info;

    public int $shipping_delay;
}
