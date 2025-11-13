<?php

namespace Checkout\Payments\Sessions;

class PaymentMethodConfiguration
{
    /**
     * Configuration options specific to Apple Pay payments.
     * @var ApplePay
     */
    public $applepay;

    /**
     * Configuration options specific to card payments.
     * @var Card
     */
    public $card;

    /**
     * Configuration options specific to Google Pay payments.
     * @var GooglePay
     */
    public $googlepay;

    /**
     * Configuration options specific to stored card payments.
     * @var StoredCard
     */
    public $stored_card;
}
