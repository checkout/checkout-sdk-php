<?php

namespace Checkout\Instruments\Four\Create;

use Checkout\Common\InstrumentType;

abstract class CreateInstrumentRequest
{
    /**
     * @var InstrumentType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
