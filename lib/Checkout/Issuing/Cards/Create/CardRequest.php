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

    /**
     * @var array
     */
    public $metadata;

    /**
     * Date for the card to be automatically revoked. Must be after the current date and date only in the
     * form yyyy-mm-dd.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var string
     */
    public $revocation_date;

    /**
     * ISO 8601 date scheduling the card's activation. Two formats are supported: date only (YYYY-MM-DD,
     * treated as midnight UTC), or date with round hour (YYYY-MM-DDTHH:mmZ in UTC, or
     * YYYY-MM-DDTHH:mm±HH:mm with offset). Only round hours are allowed when a time is provided (HH:00).
     * [Optional]
     * @var string
     */
    public $activation_date;
}
