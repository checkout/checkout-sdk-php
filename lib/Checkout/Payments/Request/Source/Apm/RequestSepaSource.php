<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestSepaSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$sepa);
    }

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var string
     */
    public $account_number;

    /**
     * @var string
     */
    public $bank_code;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string
     */
    public $mandate_id;

    /**
     * @var string
     */
    public $date_of_signature;

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
