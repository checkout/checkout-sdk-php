<?php

namespace Checkout\Instruments\Four\Create;

abstract class CreateInstrumentRequest
{
    //InstrumentType
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }


}
