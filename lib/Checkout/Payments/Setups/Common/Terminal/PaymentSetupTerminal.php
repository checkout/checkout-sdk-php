<?php

namespace Checkout\Payments\Setups\Common\Terminal;

class PaymentSetupTerminal
{
    /**
     * Terminal identifier.
     * [Optional]
     * min 8 characters, max 8 characters
     * @var string
     */
    public $id;

    /**
     * The local date and time on the terminal, in ISO 8601 format.
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var string
     */
    public $local_date_time;
}
