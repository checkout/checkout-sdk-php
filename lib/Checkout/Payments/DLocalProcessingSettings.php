<?php

namespace Checkout\Payments;

use Checkout\Common\Country;

class DLocalProcessingSettings
{
    /**
     * @var Country
     */
    public $country;

    /**
     * @var Payer
     */
    public $payer;

    /**
     * @var Installments
     */
    public $installments;

}
