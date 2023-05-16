<?php

namespace Checkout\Issuing\Cards\Create;

use Checkout\Issuing\CardType;

class PhysicalCardRequest extends CardRequest
{
    public function __construct()
    {
        parent::__construct(CardType::$physical);
    }

    /**
     * @var ShippingInstructions
     */
    public $shipping_instructions;
}
