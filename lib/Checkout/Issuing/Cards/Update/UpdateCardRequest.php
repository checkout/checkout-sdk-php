<?php

namespace Checkout\Issuing\Cards\Update;

use Checkout\Common\CardMetadata;

class UpdateCardRequest
{
    /**
     * Your reference.
     * [Optional]
     * max 256 characters
     * Example: X-123456-N11
     * @var string
     */
    public $reference;

    /**
     * User's metadata.
     * [Optional]
     * @var CardMetadata
     */
    public $metadata;

    /**
     * The card's expiration month.
     * [Optional]
     * Format: int32
     * min 1
     * max 12
     * Example: 5
     * @var int
     */
    public $expiry_month;

    /**
     * The card's expiration year.
     * [Optional]
     * Format: int32
     * min 4 characters, max 4 characters
     * Example: 2025
     * @var int
     */
    public $expiry_year;

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

    /**
     * Deprecated. Use `scheduled_revocation_date` instead.
     *
     * Date scheduling the card's automatic revocation. If you provide both fields, the
     * `scheduled_revocation_date` value overrides this value.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var string
     * @deprecated Use $scheduled_revocation_date instead.
     */
    public $revocation_date;

    /**
     * The card will be revoked at midnight UTC on the date specified. Overrides the deprecated
     * `revocation_date` if both are provided.
     * [Optional]
     * Format: date (YYYY-MM-DD, time is midnight UTC)
     * Example: 2027-03-12
     * @var string|null
     */
    public $scheduled_revocation_date;

    /**
     * Set the card's status to `active` to activate an `inactive` or `suspended` card.
     *
     * If you submit this field, you cannot specify a `scheduled_activation_date`. If you do, you
     * receive a `scheduled_activation_date_conflicts_with_activation` error.
     * [Optional]
     * Enum: "active"
     * @var string
     */
    public $status;
}
