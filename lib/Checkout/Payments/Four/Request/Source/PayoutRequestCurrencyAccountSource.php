<?php

namespace Checkout\Payments\Four\Request\Source;

class PayoutRequestCurrencyAccountSource extends PayoutRequestSource
{
    public function __construct()
    {
        parent::__construct(PayoutSourceType::$currencyAccount);
    }

    /**
     * @var string
     */
    public $id;
}
