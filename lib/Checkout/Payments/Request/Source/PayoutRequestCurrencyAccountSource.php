<?php

namespace Checkout\Payments\Request\Source;

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
