<?php

namespace Checkout\Payments;

/**
 * The network token type, used by the network token card payout destination
 * (PaymentRequestNetworkTokenDestination::$token_type).
 * Enum: "vts" "mdes" "applepay" "googlepay"
 */
class NetworkTokenType
{
    public static $vts = "vts";
    public static $mdes = "mdes";
    public static $applepay = "applepay";
    public static $googlepay = "googlepay";
}
