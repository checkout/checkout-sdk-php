<?php

namespace Checkout\Issuing\Cards\Renew;

use Checkout\Common\CardMetadata;
use Checkout\Issuing\Cards\Create\ShippingInstructions;

class RenewCardRequest
{
    /**
     * The name to display on the card.
     * @var string
     */
    public $display_name;

    /**
     * @var ShippingInstructions
     */
    public $shipping_instructions;

    /**
     * Your reference.
     * @var string
     */
    public $reference;

    /**
     * User's metadata
     * @var CardMetadata
     */
    public $metadata;
}
