<?php

namespace Checkout\Payments\Four\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Four\Request\Source\AbstractRequestSource;

class RequestAlipayPlusCNSource extends AbstractRequestSource
{
    /**
     * @var TerminalType
     */
    public $terminal_type;

    /**
     * @var OsType
     */
    public $os_type;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$alipay_cn);
    }
}
