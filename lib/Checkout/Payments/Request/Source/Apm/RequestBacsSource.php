<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

/**
 * Bacs Direct Debit source.
 */
class RequestBacsSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$bacs);
    }

    /**
     * The Bacs Direct Debit instrument ID.
     * [Required]
     * Pattern: ^(src)_(\w{26})$
     * @var string
     */
    public $id;
}
