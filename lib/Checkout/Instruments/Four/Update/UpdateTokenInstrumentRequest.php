<?php

namespace Checkout\Instruments\Four\Update;

use Checkout\Common\InstrumentType;

class UpdateTokenInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$token);
    }

    /**
     * @var string
     */
    public $token;
}
