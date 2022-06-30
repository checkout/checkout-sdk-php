<?php

namespace Checkout\Payments\Four\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Four\Request\Source\AbstractRequestSource;

class RequestAlipayPlusGCashSource extends AbstractRequestSource
{
    /**
     * @var string value of TerminalType
     */
    public $terminal_type;

    /**
     * @var string OsType
     */
    public $os_type;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$gcash);
    }
}
