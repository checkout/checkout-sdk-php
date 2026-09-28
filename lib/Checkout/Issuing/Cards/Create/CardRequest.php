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
     * Enum: "virtual" "physical"
     * Example: virtual
     * @var string value of Checkout\Issuing\CardType
     */
    public $type;

    /**
     * The cardholder's unique identifier.
     * [Required]
     * ^crh_[a-z0-9]{26}$
     * min 30 characters, max 30 characters
     * Example: crh_d3ozhf43pcq2xbldn2g45qnb44
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
     * max 256 characters
     * Example: X-123456-N11
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
     * Example: JOHN KENNEDY
     * @var string
     */
    public $display_name;

    /**
     * Sets whether to activate the newly created card upon creation. If set to false, the cardholder will
     * not be able to process transactions until you activate the card.
     * [Optional]
     * Default: true
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
     * Deprecated. Use `scheduled_revocation_date` instead.
     *
     * Date scheduling the card's automatic revocation.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var string
     * @deprecated Use $scheduled_revocation_date instead.
     */
    public $revocation_date;

    /**
     * The card will be revoked at midnight UTC on the date specified.
     * [Optional]
     * Format: date (YYYY-MM-DD, time is midnight UTC)
     * Example: 2027-03-12
     * @var string|null
     */
    public $scheduled_revocation_date;

    /**
     * Date scheduling the card's first activation. Only applies to the initial activation of a card.
     * Two formats are supported: date only (YYYY-MM-DD, treated as midnight UTC), or date with
     * round hour (YYYY-MM-DDTHH:mmZ in UTC, or YYYY-MM-DDTHH:mm+HH:mm with offset). Only round
     * hours are allowed when a time is provided (HH:00). The value must be at least the next round
     * hour after the request time.
     * [Optional]
     * Example: 2026-06-01T10:00Z
     * @var string|null
     */
    public $scheduled_activation_date;
}
