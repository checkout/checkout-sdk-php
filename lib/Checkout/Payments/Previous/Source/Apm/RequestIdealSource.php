<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class RequestIdealSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$ideal);
    }

    /**
     * @var string
     */
    public $bic;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $language;
}
