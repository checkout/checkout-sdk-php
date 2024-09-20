<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\PaymentMethodDetails;
use Checkout\Payments\Previous\Source\AbstractRequestSource;
use Checkout\Tokens\ApplePayTokenData;

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

    /**
     * @var string
     */
    public $token_type;

    /**
     * @var ApplePayTokenData
     */
    public $token_data;

    /**
     * @var PaymentMethodDetails
     */
    public $payment_method_details;
}
