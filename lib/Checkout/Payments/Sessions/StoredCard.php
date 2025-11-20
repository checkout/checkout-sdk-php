<?php

namespace Checkout\Payments\Sessions;

class StoredCard
{
    /**
     * The unique identifier for an existing customer. Enables the stored card payment method
     * if the associated customer has one or more payment instruments stored.
     * @var string
     */
    public $customer_id;

    /**
     * The unique identifiers for card Instruments. Enables stored card payment method if any one of them exists.
     * @var array of strings
     */
    public $instrument_ids;

    /**
     * The unique identifier for the payment instrument to present to the customer as the default option.
     * @var string
     */
    public $default_instrument_id;
}
