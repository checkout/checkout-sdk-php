<?php

namespace Checkout\Issuing\Cards\Update;

use Checkout\Common\CardMetadata;

class UpdateCardRequest
{
    /**
     * Your reference.
     * [Optional]
     * max 256 characters
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
     * min 1
     * max 12
     * @var int
     */
    public $expiry_month;

    /**
     * The card's expiration year.
     * [Optional]
     * min 4 characters, max 4 characters
     * @var int
     */
    public $expiry_year;

    /**
     * ISO 8601 date scheduling the card's activation. Two formats are supported: date only (YYYY-MM-DD,
     * treated as midnight UTC), or date with round hour (YYYY-MM-DDTHH:mmZ in UTC, or
     * YYYY-MM-DDTHH:mm±HH:mm with offset). Only round hours are allowed when a time is provided (HH:00).
     * [Optional]
     * @var string
     */
    public $activation_date;

    /**
     * Date for the card to be automatically revoked. Must be after the current date and date only in the
     * form yyyy-mm-dd.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var string
     */
    public $revocation_date;
}
