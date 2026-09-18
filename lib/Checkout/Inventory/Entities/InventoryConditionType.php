<?php

namespace Checkout\Inventory\Entities;

/**
 * The product's condition. Defaults to "new" when omitted.
 */
class InventoryConditionType
{
    public static $new = "new";
    public static $used = "used";
    public static $refurbished = "refurbished";
}
