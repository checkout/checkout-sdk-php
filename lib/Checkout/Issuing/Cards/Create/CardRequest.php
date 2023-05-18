<?php

namespace Checkout\Issuing\Cards\Create;

abstract class CardRequest
{
    protected function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @var string value of CardType
     */
    public $type;

    /**
     * @var string
     */
    public $cardholder_id;

    /**
     * @var CardLifetime
     */
    public $lifetime;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $card_product_id;

    /**
     * @var string
     */
    public $display_name;

    /**
     * @var bool
     */
    public $activate_card;
}
