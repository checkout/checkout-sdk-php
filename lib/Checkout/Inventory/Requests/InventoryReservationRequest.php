<?php

namespace Checkout\Inventory\Requests;

use Checkout\Inventory\Entities\InventoryReservationItem;

/**
 * The request body for creating an atomic multi-variant hold. All items are reserved together
 * or none are. The hold is protocol-neutral: it is bound to an owner_type / owner_reference
 * supplied by the calling protocol adapter (for example, a UCP session or an ACP checkout).
 */
class InventoryReservationRequest
{
    /**
     * The kind of caller that owns the hold.
     * [Required]
     * max 64 characters
     *
     * @var string
     */
    public $owner_type;

    /**
     * An opaque reference to the owning session or checkout.
     * [Required]
     * max 256 characters
     *
     * @var string
     */
    public $owner_reference;

    /**
     * The variants and quantities to hold. variant_ids must be unique within the request.
     * [Required]
     * min 1, max 45 items
     *
     * @var InventoryReservationItem[]
     */
    public $items;

    /**
     * How long the hold remains valid before it auto-expires. Defaults to 900.
     * [Optional]
     * minimum 60, maximum 3600
     *
     * @var int|null
     */
    public $ttl_seconds;
}
