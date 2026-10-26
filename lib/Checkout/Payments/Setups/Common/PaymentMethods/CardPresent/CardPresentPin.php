<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CardPresent;

/**
 * The encrypted PIN block details of a Card Present payment method.
 *
 * @deprecated card_present is no longer part of the Payment Setups API specification. There is no
 *     replacement. Will be removed in the next major version.
 */
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
