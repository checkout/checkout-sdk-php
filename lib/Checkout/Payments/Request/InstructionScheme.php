<?php

namespace Checkout\Payments\Request;

/**
 * The payment scheme type of a bank payout instruction.
 * Enum: "local" "instant"
 */
class InstructionScheme
{
    /**
     * @deprecated Not in the request schema, which only lists "local" and "instant"; still returned in
     * PayoutDetails (instruction.scheme). Retained for backwards compatibility and will be removed in the
     * next major version. There is no replacement.
     * @var string
     */
    public static $swift = "swift";
    public static $local = "local";
    public static $instant = "instant";
}
