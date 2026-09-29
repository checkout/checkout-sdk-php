<?php

namespace Checkout\Payments\Contexts;

/**
 * A key-and-value pair with merchant-specific data for the transaction.
 *
 * @deprecated Duplicates Checkout\Payments\PartnerCustomerRiskData, which maps the same
 * specification shape and is what PaymentContextsProcessing now uses. Maintaining two identical
 * classes for one schema invited drift. Retained for backwards compatibility and will be removed
 * in a future version.
 */
class PaymentContextsPartnerCustomerRiskData
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $value;
}
