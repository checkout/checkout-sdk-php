<?php

namespace Checkout\Forex;

class QuoteRequest
{
    public $source_currency;

    public $source_amount;

    public $destination_currency;

    public $destination_amount;

    public $process_channel_id;
}
