<?php

namespace Checkout\Payments\Sessions;

class StorePaymentDetailsType
{
    /**
     * Payment details storage is disabled
     */
    public $DISABLED = "disabled";
    
    /**
     * Payment details storage is enabled
     */
    public $ENABLED = "enabled";
    
    /**
     * Collect consent for payment details storage
     */
    public $COLLECT_CONSENT = "collect_consent";
}
