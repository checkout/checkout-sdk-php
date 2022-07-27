<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class RequestPayPalSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$paypal);
    }

    /**
     * @var string
     */
    public $invoice_number;

    /**
     * @var string
     */
    public $recipient_name;

    /**
     * @var string
     */
    public $logo_url;

    /**
     * @var array
     */
    public $stc;
}
