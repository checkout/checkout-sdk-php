<?php

namespace Checkout\Forex;

use Checkout\Common\AbstractQueryFilter;

class RatesQueryFilter extends AbstractQueryFilter
{
    /**
     * @var string
     */
    public $product;

    /**
     * @var string value of ForexSource
     */
    public $source;

    /**
     * @var string
     */
    public $currency_pairs;

    /**
     * @var string
     */
    public $process_channel_id;
}
