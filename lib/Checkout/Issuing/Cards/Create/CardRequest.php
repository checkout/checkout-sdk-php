<?php

namespace Checkout\Issuing\Cards\Create;

abstract class CardRequest
{
    protected function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * The card type.
     * [Required]
     * @var string value of CardType
     */
    public $type;

    /**
     * The cardholder's unique identifier.
     * [Required]
     * ^crh_[a-z0-9]{26}$
     * min 30 characters, max 30 characters
     * @var string
     */
    public $cardholder_id;

    /**
     * The duration of time during which the card will accept incoming authorizations.
     * [Optional]
     * @var CardLifetime
     */
    public $lifetime;

    /**
     * Your reference.
     * [Optional]
     * <= 256 characters
     * @var string
     */
    public $reference;

    /**
     * The card product's unique identifier. This field is required if there are multiple card products
     * associated with the entity.
     * [Required]
     * @var string
     */
    public $card_product_id;

    /**
     * The name to display on the card.
     * [Optional]
     * ^[0-9a-zA-Z.\- ]{2,26}$
     * min 2 characters, max 26 characters
     * @var string
     */
    public $display_name;

    /**
     * Sets whether to activate the newly created card upon creation. If set to false, the cardholder will
     * not be able to process transactions until you activate the card.
     * [Optional]
     * @var bool
     */
    public $activate_card;

    /**
     * User's metadata.
     * [Optional]
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
