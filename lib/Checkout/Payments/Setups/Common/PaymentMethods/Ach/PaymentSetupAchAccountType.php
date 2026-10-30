<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Ach;

/**
 * The type of ACH Direct Debit account on a Payment Setup.
 *
 * Do not use Checkout\Instruments\AchAccountType, which declares savings and checking.
 */
class PaymentSetupAchAccountType
{
    /** @var string */
    public static $savings = "savings";

    /** @var string */
    public static $current = "current";

    /** @var string */
    public static $cash = "cash";
}
