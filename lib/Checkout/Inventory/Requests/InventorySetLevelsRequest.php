<?php

namespace Checkout\Inventory\Requests;

/**
 * The request body for setting absolute stock levels on a variant.
 */
class InventorySetLevelsRequest
{
    /**
     * The absolute physical stock to set for the variant.
     * [Required]
     * minimum 0
     *
     * @var int
     */
    public $on_hand;

    /**
     * The buffer quantity to withhold from sale. Defaults to 0 when the item is created and is
     * left unchanged on update if omitted.
     * [Optional]
     * minimum 0
     *
     * @var int|null
     */
    public $safety_stock;

    /**
     * An optional free-text reason recorded in the ledger. Must not contain personal data.
     * [Optional]
     * max 256 characters
     *
     * @var string|null
     */
    public $reason;
}
