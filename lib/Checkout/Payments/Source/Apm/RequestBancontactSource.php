<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\Country;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestBancontactSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$bancontact);
    }

    /**
     * @var Country
     */
    public $payment_country;

    /**
     * @var string
     */
    public $account_holder_name;

    /**
     * @var string
     */
    public $billing_descriptor;

    /**
     * @var string
     */
    public $language;
}
