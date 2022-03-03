<?php

namespace Checkout\Apm\Klarna;

class KlarnaProduct
{
    public string $name;

    public int $quantity;

    public int $unit_price;

    public int $tax_rate;

    public int $total_amount;

    public int $total_tax_amount;
}
