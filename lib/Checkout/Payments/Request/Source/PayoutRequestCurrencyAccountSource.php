<?php

namespace Checkout\Payments\Request\Source;

/**
 * A currency account source, with type "currency_account".
 * Used by card payouts, bank payouts and unreferenced refunds.
 */
class PayoutRequestCurrencyAccountSource extends PayoutRequestSource
{
    public function __construct()
    {
        parent::__construct(PayoutSourceType::$currencyAccount);
    }

    /**
     * The ID of the currency account that will fund the payout or the unreferenced refund.
     * For bank payouts, its holding currency must match the payout's currency.
     * [Required]
     * ^(ca)_(\w{26})$
     * min 29 characters
     * max 29 characters
     * @var string
     */
    public $id;
}
