<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class RequestKnetSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$knet);
    }

    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $user_defined_field1;

    /**
     * @var string
     */
    public $user_defined_field2;

    /**
     * @var string
     */
    public $user_defined_field3;

    /**
     * @var string
     */
    public $user_defined_field4;

    /**
     * @var string
     */
    public $user_defined_field5;

    /**
     * @var string
     */
    public $card_token;

    /**
     * @var string
     */
    public $ptlf;
}
