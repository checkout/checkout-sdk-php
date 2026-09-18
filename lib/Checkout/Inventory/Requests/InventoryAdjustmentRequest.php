<?php

namespace Checkout\Inventory\Requests;

/**
 * The request body for applying a relative adjustment to a variant's on-hand stock.
 */
class InventoryAdjustmentRequest
{
    /**
     * The identifier of the variant to adjust. The variant must already exist.
     * [Required]
     * max 128 characters
     *
     * @var string
     */
    public $variant_id;

    /**
     * The signed change to apply to on_hand. Must be non-zero. A negative delta that would
     * drive on_hand below zero is rejected with 409 conflict.
     * [Required]
     *
     * @var int
     */
    public $delta;

    /**
     * A required free-text reason recorded in the ledger (for example, damage or found stock).
     * Must not contain personal data.
     * [Required]
     * min 1, max 256 characters
     *
     * @var string
     */
    public $reason;
}
