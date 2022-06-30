<?php

namespace Checkout\Forex;

class QuoteRequest
{
    /**
     * @var string value of Currency
     */
    public $source_currency;

    /**
     * @var int
     */
    public $source_amount;

    /**
     * @var string value of Currency
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
