<?php

namespace Checkout\Sources\Previous;

use Checkout\Common\Address;

class SepaSourceRequest extends SourceRequest
{
    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var SourceData
     */
    public $source_data;

    public function __construct()
    {
        parent::__construct(SourceType::$sepa);
    }
}
