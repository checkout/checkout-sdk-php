<?php

namespace Checkout\Instruments\Four\Create;

abstract class CreateInstrumentRequest
{
    //InstrumentType
    public string $type;

    protected function __construct(string $type)
    {
        $this->type = $type;
    }


}
