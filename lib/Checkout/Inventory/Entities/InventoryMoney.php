<?php

namespace Checkout\Inventory\Entities;

/**
 * A monetary amount in the currency's minor units.
 */
class InventoryMoney
{
    /**
     * The amount in the minor currency unit.
     * [Required]
     *
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO 4217 currency code.
     * [Required]
     * min 3, max 3 characters
     *
     * @var string
     */
    public $currency;
}
