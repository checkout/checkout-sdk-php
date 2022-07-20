<?php

namespace Checkout\Payments;

class MerchantInitiatedReason
{
    public static $DELAYED_CHARGE = "Delayed_charge";
    public static $RESUBMISSION = "Resubmission";
    public static $NO_SHOW = "No_show";
    public static $REAUTHORIZATION = "Reauthorization";
}
