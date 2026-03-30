<?php

namespace Checkout\Issuing\Cards\Create;

use Checkout\Common\Address;

class ShippingInstructions
{
    /**
     * The address to ship the physical card to. (Required)
     * @var Address
     */
    public $shipping_address;

    /**
     * @var string
     */
    public $recipient_address;

    /**
     * @var string
     */
    public $additional_comment;
}
