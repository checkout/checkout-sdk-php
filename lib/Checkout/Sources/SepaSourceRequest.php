<?php

namespace Checkout\Sources;

class SepaSourceRequest extends SourceRequest
{

    // Address
    public $billing_address;

    // SourceData
    public $source_data;

    public function __construct()
    {
        parent::__construct(SourceType::$sepa);
    }

}
