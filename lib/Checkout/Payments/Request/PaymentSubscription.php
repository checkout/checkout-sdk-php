<?php

namespace Checkout\Payments\Request;

class PaymentSubscription
{
    /**
     * The ID or reference linking a series of recurring payments together.
     * [Optional]
     * max 50 characters
     * @var string|null $id
     */
    public $id;
}
