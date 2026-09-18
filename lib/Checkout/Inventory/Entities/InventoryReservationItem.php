<?php

namespace Checkout\Inventory\Entities;

/**
 * A single variant and quantity within a reservation.
 */
class InventoryReservationItem
{
    /**
     * The identifier of the variant to hold. The variant must already exist.
     * [Required]
     * max 128 characters
     *
     * @var string
     */
    public $variant_id;

    /**
     * The quantity to hold for this variant.
     * [Required]
     * minimum 1
     *
     * @var int
     */
    public $quantity;
}
