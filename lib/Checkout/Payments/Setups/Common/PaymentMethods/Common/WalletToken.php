<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

use DateTime;

/**
 * The Checkout.com token for a digital wallet payment method (Apple Pay or Google Pay).
 */
class WalletToken
{
    /**
     * The token identifier, prefixed by tok_.
     * [Required]
     * ^(tok)_(\w{26})$
     * @var string
     */
    public $id;

    /**
     * The date and time the token expires, in ISO 8601 format.
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var DateTime
     */
    public $expires_on;
}
