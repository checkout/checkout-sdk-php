<?php

namespace Checkout\Payments\Request;

/**
 * The payment scheme type of a bank payout instruction.
 * Enum: "local" "instant"
 */
class InstructionScheme
{
    /**
     * @deprecated Not in the current specification, which only lists "local" and "instant". Retained
     * for backwards compatibility and will be removed in a future version.
     * @var string
     */
    public static $swift = "swift";
    public static $local = "local";
    public static $instant = "instant";
}
