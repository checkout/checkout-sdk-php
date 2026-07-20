<?php

namespace Checkout\Accounts;

class ProcessingDetails
{
    /**
     * @var int the estimated annual processing volume in minor units without decimals
     */
    public $annual_processing_volume;

    /**
     * @var int the estimated average transaction value in minor units without decimals
     */
    public $average_transaction_value;

    /**
     * @var int the estimated average order fulfillment time in days
     */
    public $average_order_fulfillment_time;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var array of Country the countries the sub-entity intends to sell to
     */
    public $target_countries;

    /**
     * @var ProcessingDetailsPayments
     */
    public $payments;
}
