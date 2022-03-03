<?php

namespace Checkout\Forex;

class QuoteRequest
{
    public string $source_currency;

    public int $source_amount;

    public string $destination_currency;

    public int $destination_amount;

    public string $process_channel_id;
}
