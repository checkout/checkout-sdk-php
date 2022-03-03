<?php

namespace Checkout\Sources;

use Checkout\Common\Address;

class SepaSourceRequest extends SourceRequest
{

    public Address $billing_address;

    public SourceData $source_data;

    public function __construct()
    {
        parent::__construct(SourceType::$sepa);
    }

}
