<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Ach;

/**
 * The type of ACH account holder.
 */
class AchAccountHolderType
{
    /** @var string */
    public static $individual = "individual";

    /** @var string */
    public static $corporate = "corporate";

    /** @var string */
    public static $government = "government";
}
