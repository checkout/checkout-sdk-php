<?php

namespace Checkout\Issuing\Cards\Update;

use Checkout\Common\CardMetadata;

class UpdateCardRequest
{
    /**
     * Your reference.
     * @var string
     */
    public $reference;

    /**
     * User's metadata.
     * @var CardMetadata
     */
    public $metadata;

    /**
     * The card's expiration month.
     * @var int
     */
    public $expiry_month;

    /**
     * The card's expiration year.
     * @var int
     */
    public $expiry_year;
}
