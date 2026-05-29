<?php

namespace Checkout\Payments\Request;

class CardPayoutItem
{
    /**
     * The item type. For example, `physical` or `digital`.
     * This field is used to provide item level metadata where applicable.
     * [Optional]
     * Enum: "digital" "physical"
     * @var string|null $type
     */
    public $type;

    /**
     * The digital item type.
     * If `type` is set to `digital`, this field is required.
     * [Optional]
     * Enum: "blockchain" "cbdc" "cryptocurrency" "nft" "stablecoin"
     * @var string|null $sub_type
     */
    public $sub_type;
}
