<?php

namespace Checkout\Instruments\Create;

use Checkout\Common\Phone;

class CreateCustomerInstrumentRequest
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var bool
     */
    public $default;
}
