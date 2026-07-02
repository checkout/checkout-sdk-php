<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CardPresent;

class CardPresentPin
{
    /**
     * The identifier of the key set used to encrypt the PIN block.
     * [Required] writeOnly
     * @var string
     */
    public $key_set_id;

    /**
     * The encrypted PIN block.
     * [Required] writeOnly
     * @var string
     */
    public $block;

    /**
     * The format of the encrypted PIN block.
     * [Required] writeOnly
     * @var string
     */
    public $block_format;
}
