<?php

namespace Checkout\Payments\Setups\Common\Settings;

/**
 * Settings for the Payment Setup.
 */
class Settings
{
    /**
     * The URL to redirect the customer to, if the payment is successful. For payment methods with a
     * redirect, this value overrides the default success redirect URL configured on your account.
     * [Optional]
     * Format: uri
     * max 255 characters
     * @var string
     */
    public $success_url;

    /**
     * The URL to redirect the customer to, if the payment is unsuccessful. For payment methods with a
     * redirect, this value overrides the default failure redirect URL configured on your account.
     * [Optional]
     * Format: uri
     * max 255 characters
     * @var string
     */
    public $failure_url;

    /**
     * Indicates whether to capture the payment immediately.
     * [Optional]
     * Default: true
     * @var bool
     */
    public $capture;

    /**
     * The list of payment methods excluded from the Payment Setup, for example card, klarna or stcpay.
     * [Optional]
     * @var string[]
     */
    public $excluded_payment_methods;
}
