<?php

namespace Checkout\Forex;

use Checkout\Common\Currency;

class QuoteRequest
{
    /**
     * @var Currency
     */
    public $source_currency;

    /**
     * @var int
     */
    public $source_amount;

    /**
     * @var Currency
     */
    public $destination_currency;

    /**
     * @var int
     */
    public $destination_amount;

    /**
     * @var string
     */
    public $process_channel_id;
}
